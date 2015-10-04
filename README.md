
# UK Street Level Crime Updater

A set of tools for fetching a geographic subset of data.police.uk street level crimes & optionally uploading to a Socrata dataset.

## Install

Clone the repository & run ```composer install``` in the root folder.

All scripts reside in the ```bin``` folder.

## Configure

To create the geographic subset, you will require a file containing the boundary points. The points should be in the format :-

    lat1,lng1:lat2,lng2:...:latN,lngN
    
We've included a Bath & North East Somerset boundary file by way of example.

## Fetch crime data

To fetch the crime data use the following :-

    php fetch-all.php path-to/boundary-file.txt [look-back-months]
    
The optional second argument specifies the maximum number of months to look back before this month. 
For example, to update the past year's crimes do the following :- 

    php fetch-all.php path-to/boundary-file.txt 12

It outputs a flat JSON encoding of the data on stdout.

## Convert to CSV

Before uploading the data to Socrata we need to convert the JSON format to CSV format.

    php to-csv.php [path-to/map-file.json] < path-to/json-file.json 
    
The script takes the JSON file on stdin and puts a CSV file to stdout.

The optional argument allows you to specify a mapping between the extracted fields and your target fields. 
Fields can be omitted. See ```config/default-map.json``` for the default fields.
    
The CSV file can be uploaded directly to Socrata to allow you to initially set up the dataset.

## Upload the data to Socrata

__Remember to add a Row Identifier for your dataset.__

    php update-socrata.php path-to/socrata-config.json [replace|update]< path-to/csv-file.csv
    
The first required argument specifies a config file, containing your Socrata credentials. See ```config/example-socrata.json```.

The second optional argument specifies whether the dataset should be updated (upsert) or replaced. The default is update.
    
## Putting it all together

We can chain all of these scripts together :-

    php fetch-all.php path-to/boundary-file.txt 12 | php to-csv.php | php update-socrata.php path-to/socrata-config.json
    

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
