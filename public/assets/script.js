let last_inserted_id;
let table_update_interval;
let last_message_timout;
let show_form = false;

$( document ).ready(function() {
    last_inserted_id = $("#last_inserted").val();

    table_update_interval = setInterval(RefreshTable, 5000);

    $("#show_form").click(function() {
        show_form = !show_form;
        if(show_form) {
            $("#form").fadeIn();
            $("#show_form").html("Add Data ˄")
        }
        else {
            $("#form").fadeOut();
            $("#show_form").html("Add Data ˅")
        }
    });

    // Upload Button Clicked
    $("#upload").click(function() {
        $("#upload").prop( "disabled", true );
        $("#file").prop( "disabled", true );
        ShowMessage("Uploading...","#34568B", false);
        clearInterval(table_update_interval);
        if($("#file").val() == "") {
            $("#upload").prop( "disabled", false );
            $("#file").prop( "disabled", false );
            ShowMessage("Please choose file.", "red"), true;
            table_update_interval = setInterval(RefreshTable, 5000);
            return 0;
        }

        let file_parts = $("#file").val().split(".");
        if( file_parts[file_parts.length - 1] != "csv") {
            $("#upload").prop( "disabled", false );
            $("#file").prop( "disabled", false );
            ShowMessage("Please choose CSV file.", "red", true);
            table_update_interval = setInterval(RefreshTable, 5000);
        }
        let formData = new FormData();
        formData.append("file", document.getElementById("file").files[0]);
        formData.append("action", "uploadFile");
        $.ajax({
            url: 'api.php',
            data: formData,
            contentType: false,
            processData: false,
            cache: false,
            type: 'POST',
            success: function(data) {
                if(data.status == "ERROR") {
                    ShowMessage(data.message,"red", true);
                }
                if(data.status == "OK") {
                    ShowMessage(data.message,"green", true);
                }
                $("#upload").prop( "disabled", false );
                $("#file").prop( "disabled", false );
                $("#file").val("");
                RefreshTable();
                table_update_interval = setInterval(RefreshTable, 5000);
            },
        });
    });


});

function ShowMessage(text, color, out) {
    $("#message").fadeOut();
    $("#message").html("<b style='color:" + color + "'>" + text + "</b>");
    $("#message").fadeIn();
    if(out) {
        clearTimeout(last_message_timout);
        last_message_timout = setTimeout(() => {$("#message").fadeOut()}, 3000);
    }
}

function RefreshTable() {
    $.ajax({
        url: 'api.php',
        data: {"action": "getStats", "last_inserted" : last_inserted_id},
        type: 'POST',
        success: function(data) {
            if(data.status == "UPDATE") {
                last_inserted_id = data.last_inserted;
                let inner_html = "<tr>";
                inner_html += "<th>Customer ID</th>";
                inner_html += "<th>Number of calls within same continent</th>";
                inner_html += "<th>Total Duration of calls within same continent</th>";
                inner_html += "<th>Total Numbers of all calls</th>";
                inner_html += "<th>Total Duration of all calls</th>";
                inner_html += "</tr>";
                data.stats.forEach(function(value, index, array) {
                    inner_html += "<tr>";
                    inner_html += "<td>" + value.customer_id + "</td>";
                    inner_html += "<td>" + value.total_calls_same_continent + "</td>";
                    inner_html += "<td>" + value.total_duration_same_continent + "</td>";
                    inner_html += "<td>" + value.total_calls + "</td>";
                    inner_html += "<td>" + value.total_duration + "</td>";
                    inner_html += "</tr>";
                });
                $("#customers").html(inner_html);
            }
        },
    });
}