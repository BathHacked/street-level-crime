
# UK Street Level Crime Updater

A set of tools for fetching a geographic subset of data.police.uk street level crimes & optionally uploading to a Socrata dataset.

## Install

Clone the repository & run ```composer install``` in the root folder.

All scripts reside in the ```bin``` folder.

## Configure

To create the geographic subset, you will require a file containing the boundary points. The points should be in the format :-

    lat1,lng1:lat2,lng2:...:latN,lngN

If you wish to upload data to a Socrata dataset, copy the file ```config/socrata.php.example``` to ```config/socrata.php``` & edit the settings accordingly.

## Fetch crime data

To fetch the crime data use the following :-

    php fetch-all.php path-to-boundary-file.txt
    
The script takes a second integer argument which allows you to specify the maximum number of months to look back before this month. 
For example, to update the past year's crimes do the following :- 

    php fetch-all.php path-to-boundary-file.txt 12

It outputs a JSON encoding of the data on stdout.

## Convert to CSV

Before uploading the data to Socrata we need to convert the JSON format to CSV format.

    php to-csv.php < path-to-json-file.json
    
The script takes the JSON file on stdin and puts a CSV file to stdout.

The CSV file can be uploaded directly to Socrata to allow you to initially set up the dataset.

## Upload the data to Socrata

This will do an _upsert_ on your dataset based on the identifier for each crime. __Remember to make ```id``` the Row Identifier for your dataset.__

    php update-socrata.php < path-to-csv-file.csv
    
## Putting it all together

We can chain all of these scripts together :-

    php fetch-all.php path-to-boundary-file.txt 12 | php to-csv.php | php update-socrata.php
    

## License

The MIT License (MIT)

Copyright (c) Bath: Hacked

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
