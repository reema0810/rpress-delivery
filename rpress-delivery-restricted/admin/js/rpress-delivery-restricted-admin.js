jQuery(document).ready(function ($) {
	// Check the value of the selected radio button
	var selectedValue = $("input[name='rpress_settings\\[select_delivery_location_method\\]']:checked").val();
	// Now you can use the selectedValue variable
	if (selectedValue === "option1") {
		// ZIP-based delivery is selected
		$("#rpress_settings\\[restricted_zip_code\\]").parent().parent().css("display", "");
		$("#rpress_settings\\[google_map_api_key\\]").parent().parent().css("display", "none");
		$("#rpress_settings\\[store_longitude\\]").parent().parent().css("display", "none");
		$("#rpress_settings\\[store_latitude\\]").parent().parent().css("display", "none");
		$("#rpress_settings\\[distance_unit_select\\]").parent().parent().css("display", "none");
		$("#rpress_settings\\[distance_unit_text\\]").parent().parent().css("display", "none");
		console.log(selectedValue);
	} else if (selectedValue === "option2") {
		// Location-based delivery is selected
		$("#rpress_settings\\[restricted_zip_code\\]").parent().parent().css("display", "none");
		$("#rpress_settings\\[google_map_api_key\\]").parent().parent().css("display", "");
		$("#Srpress_settings\\[store_longitude\\]").parent().parent().css("display", "");
		$("#rpress_settings\\[store_latitude\\]").parent().parent().css("display", "");
		$("#rpress_settings\\[distance_unit_select\\]").parent().parent().css("display", "");
		$("#rpress_settings\\[distance_unit_text\\]").parent().parent().css("display", "");
		console.log(selectedValue);
	} else {
		// No radio button is selected
		console.log('null');
	}
	$("input[name='rpress_settings\\[select_delivery_location_method\\]']").change(function () {
		var selectedValue = $(this).val(); // Get the value of the selected radio button
		// Perform an action based on the selected value
		if (selectedValue === "option1") {
			// ZIP-based delivery is selected
			$("#rpress_settings\\[restricted_zip_code\\]").parent().parent().css("display", "");
			$("#rpress_settings\\[google_map_api_key\\]").parent().parent().css("display", "none");
			$("#rpress_settings\\[store_longitude\\]").parent().parent().css("display", "none");
			$("#rpress_settings\\[store_latitude\\]").parent().parent().css("display", "none");
			$("#rpress_settings\\[distance_unit_select\\]").parent().parent().css("display", "none");
			$("#rpress_settings\\[distance_unit_text\\]").parent().parent().css("display", "none");
			console.log(selectedValue);
		} else if (selectedValue === "option2") {
			// Location-based delivery is selected
			$("#rpress_settings\\[restricted_zip_code\\]").parent().parent().css("display", "none");
			$("#rpress_settings\\[google_map_api_key\\]").parent().parent().css("display", "");
			$("#rpress_settings\\[store_longitude\\]").parent().parent().css("display", "");
			$("#rpress_settings\\[store_latitude\\]").parent().parent().css("display", "");
			$("#rpress_settings\\[distance_unit_select\\]").parent().parent().css("display", "");
			$("#rpress_settings\\[distance_unit_text\\]").parent().parent().css("display", "");
		} else {
			// No radio button is selected
			console.log('null');
		}
	});
	function getSelectedValue() {
		// Get all radio buttons with the name "gender"
		var radioButtons = document.getElementsByName("Select Delivery Location Method");
		// Initialize a variable to store the selected value
		var selectedValue = "";
		// Loop through the radio buttons to find the selected one
		for (var i = 0; i < radioButtons.length; i++) {
			if (radioButtons[i].checked) {
				// Set the selected value to the value of the checked radio button
				selectedValue = radioButtons[i].value;
				break; // Exit the loop once a selection is found
			}
		}
		// Display the selected value on the web page
		document.getElementById("selectedValue").textContent = selectedValue;
	}
});


