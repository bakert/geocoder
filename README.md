geocoder
========

Dead simple wrapper around Google Maps Geolocation service

Since changes in mid-2018 it is no longer possible to access this service without an API key with associated payment
method but low volume usage is free. The API key used must not have "referer restrictions".

Usage:

    $apiKey = 'AIzaSFJKEALKvPLVVC3IgIEBFRZnqn1j45_ZKPo'; // This needs to be a valid Google API Key. This is an example.
    $addrs = Geocoder::simpleGeocode("High Street, Kensington, London");
    $addrs = Geocoder::simpleGeocode("Mount Everest");
    $addrs = Geocoder::simpleGeocode("90210");
