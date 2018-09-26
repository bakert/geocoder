<?php

/*
A PHP wrapper for the Google Maps API geocoding services.
Requires json_decode be available.
Licensed under the MIT/X11 license (see below).
Thomas David Baker, <bakert@gmail.com>

Example:

// Can take streetnames ("Broadway, London"), longer addresses ("High Street, Kensington, London"),
// postcodes/zipcodes ("SW1A 1AA", "90210") or points of interest ("Buckingham Palace", "Mount Everest").

$apiKey = 'AIzaSFJKEALKvPLVVC3IgIEBFRZnqn1j45_ZKPo'; // This needs to be a valid Google API Key. This is an example.
$results = Geocoder::simpleGeocode("Broadway", $apiKey);
foreach ($results as $result) {
    echo $result['address'] . "\n";
    echo $result['longitude'] . "\n";
    echo $result['latitude'] . "\n";
}
*/

class Geocoder {

    // Get an array of possible geocoding matches for an address or an empty array if none found.
    // Matches are of the type array('q' => <original search string>, 'address' => <best effort at a street address',
    // 'longitude' => <longitude>, 'latitude' => <latitude>).
    // Return value of null signals an error somewhere along the way.
    public static function simpleGeocode($addr, $apiKey) {
        $data = self::geocode($addr, $apiKey);
        if (! ($data && isset($data['status']))) {
            return null;
        }
        $status = $data['status'];
        if ($status !== 'OK') {
            return null;
        }
        $rs = array();
        foreach ($data['results'] as $result) {
           $rs[] = self::parseResult($result);
        }
        return $rs;
    }

    // Get the Google Maps API JSON output as an assoc. array for the specified address.
    // Return value of null means the data could not be retrieved, false means could not be decoded.
    public static function geocode($addr, $apiKey) {
        $encAddr = rawurlencode($addr);
        $encKey = rawurlencode($apiKey);
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$encAddr&sensor=false&key=$encKey";
        $json = file_get_contents($url);
        if (! $json) { return null; }
        $data = json_decode($json, true);
        if (! $data) { return false; }
        return $data;
    }

    // Takes a member of the Google Maps API 'results' array and converts it to something flatter and more manageable.
    // Return value is assoc array with keys 'address', 'longitude' and 'latitude'
    public static function parseResult($result) {
        $r = array();
        $r['address'] = $result['formatted_address'];
        if (isset($result['geometry']['location'])) {
            $r['latitude'] = $result['geometry']['location']['lat'];
            $r['longitude'] = $result['geometry']['location']['lng'];
        } else {
            $r['latitude'] = null;
            $r['longitude'] = null;
        }
        return $r;
    }

}

/*
Copyright (c) 2008-2013 Thomas David Baker

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.
*/
