# Call Statistics

This project provides a PHP script to generate call statistics. The script analyzes call data to determine the number and duration of calls made within the same continent and across different continents.

## Features

* Uploads CSV file containing call data (Customer ID, Call Date, Duration, Dialed Phone Number, Customer IP)
* Displays a table with the following statistics for each customer:
    * Customer ID
    * Number of calls within the same continent
    * Total duration of calls within the same continent
    * Total number of all calls
    * Total duration of all calls
* Determines the continent of the initiating IP using the ipstack.com service (API Key provided)
* Determines the continent of the dialed phone number using the Geoname countryInfo.txt database (download required)
