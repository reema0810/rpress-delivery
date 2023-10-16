jQuery(document).ready(function ($) {
    //Get the delivery restriction method from the global variable
    var deliveryRestrictedMethod = DeliveryrestrictedVars.delivery_restricted_method;
    //Create an object to store selected place data
    var selectedPlace = {};
    //Handle AJAX requests before they are sent
    $(document).ajaxSend(function (ev, xhr, settings) {
        if (settings.data) {
            //Check if the AJAX request is related to service slot checking
            if (settings.data.indexOf('rpress_check_service_slot') !== -1 || settings.data.indexOf('rp_check_service_slot') !== -1) {
                //Get the value of the 'rp_delivery_input' input element
                var delivery_input = $('#rp_delivery_input').val();
                var parameters = {};
                if (deliveryRestrictedMethod === 'location_based') {
                    //For location-based delivery, assign the input to 'delivery_location'
                    parameters.delivery_location = delivery_input;
                    if ($('#rp_delivery_input').data('lat') && $('#rp_delivery_input').data('lng')) {
                        //Include additional location data if available
                        parameters.user_lat = $('#rp_delivery_input').data('lat');
                        parameters.user_lng = $('#rp_delivery_input').data('lng');
                        parameters.street_address = selectedPlace.street_address;
                        parameters.city = selectedPlace.city;
                        parameters.postcode = selectedPlace.postcode;
                    }
                } else if (deliveryRestrictedMethod === 'zip_based') {
                    //For zip-based delivery, assign the input to 'delivery_zip'
                    parameters.delivery_zip = delivery_input;
                }
                //Add the parameters to the AJAX request data
                settings.data += '&' + $.param(parameters);
            }
        }
    });
    //Handle AJAX requests after they are completed
    $(document).ajaxComplete(function (event, xhr, settings) {
        console.log('ajax cmplete');
        if (settings.data) {
            //Check if the AJAX request is related to showing delivery options
            if (settings.data.indexOf('rpress_show_delivery_options') != -1) {
                var parameters = {};
                if (deliveryRestrictedMethod === 'location_based') {
                    //For location-based delivery, assign the entire input to 'delivery_location'
                    var autocomplete = setGmap();
                    var selectedLocation;
                    //Listen for place selection with Google Maps Autocomplete
                    google.maps.event.addListener(autocomplete, 'place_changed', function () {
                        var place = autocomplete.getPlace();
                        //Check if a valid place was selected
                        if (!place.geometry || !place.geometry.location) {
                            alert('Invalid place selection');
                            return;
                        }
                        //Get latitude and longitude and update input attributes
                        selectedLocation = {
                            latitude: place.geometry.location.lat(),
                            longitude: place.geometry.location.lng()
                        };
                        $('#rp_delivery_input').attr('data-lat', selectedLocation.latitude);
                        $('#rp_delivery_input').attr('data-lng', selectedLocation.longitude);
                        //Populate selectedPlace object with address components
                        selectedPlace['street_address'] = getStreetAddress(place);
                        selectedPlace['city'] = getCity(place);
                        selectedPlace['postcode'] = getPostalCode(place);
                    });
                }
                else if (deliveryRestrictedMethod === 'zip_based') {
                    //Handle zip-based delivery (no additional action in this part)
                }
                //Add parameters to the AJAX request data
                settings.data += '&' + $.param(parameters);
            }
        }
    });
});
//Function to initialize Google Maps Autocomplete
function setGmap() {
    var locationInput = document.getElementById('rp_delivery_input');
    var autocomplete = new google.maps.places.Autocomplete(locationInput);
    return autocomplete;
}
//Function to extract and format the street address from place details
function getStreetAddress(place) {
    var extractedAddress = [];

    if (place && place.address_components) {
        for (var i = 0; i < place.address_components.length; i++) {
            var component = place.address_components[i];
            var types = component.types;
            var isRepeated = false;
            if (
                types.indexOf('country') === -1 &&
                types.indexOf('postal_code') === -1 &&
                types.indexOf('administrative_area_level_1') === -1
            ) {
                // Check if the component is already in extractedAddress
                for (var j = 0; j < extractedAddress.length; j++) {
                    if (extractedAddress[j] === component.long_name) {
                        isRepeated = true;
                        break; // Exit the loop if it's repeated
                    }
                }

                // If it's not repeated, add it to extractedAddress
                if (!isRepeated) {
                    extractedAddress.push(component.long_name);
                }
            }
        }
    }
    var resultAddress = extractedAddress.join(', ');

    return resultAddress;
}

//Function to extract the city from place details
function getCity(place) {
    for (var i = 0; i < place.address_components.length; i++) {
        var component = place.address_components[i];
        if (component.types.includes('locality')) {
            return component.long_name;
        }
    }
    return '';
}
//Function to extract the postal code from place details
function getPostalCode(place) {
    for (var i = 0; i < place.address_components.length; i++) {
        var component = place.address_components[i];
        if (component.types.includes('postal_code')) {
            return component.long_name;
        }
    }
    return '';
}

