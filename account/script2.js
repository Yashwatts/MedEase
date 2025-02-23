
        	// Attach an event listener to the refNumber input
        	document.getElementById('refNumber').addEventListener('input', function() {
            	fetchPatientData();
        	});

        	function fetchPatientData() {
            	var refNumber = document.getElementById('refNumber').value;

            	var xhr = new XMLHttpRequest();
            	xhr.open('GET', 'fetch_patient_data.php?refNumber=' + refNumber, true);

            	xhr.onload = function() {
                	if (xhr.status == 200) {
                    	var data = JSON.parse(xhr.responseText);
                    	document.getElementById('full_name').value = data.full_name;
                    	document.getElementById('age').value = data.age;
                    	document.getElementById('gender').value = data.gender;
                    	document.getElementById('chief_complaint').value = data.chief_complaint;
                    	document.getElementById('medications').value = data.medications;
                	} else {
                    	document.getElementById('notification').innerHTML = 'Error fetching data.';
                    	document.getElementById('notification').style.display = 'block';
                	}
            	};

            	xhr.send();
        	}

        	function handleInput(inputElement) {
            	const slashIndex = inputElement.value.lastIndexOf('/');
            	if (slashIndex !== -1) {
                	const caretPosition = slashIndex + 4; // Position after '<br>'
                	inputElement.value = inputElement.value.replace('/', '<br>');
                	setCaretPosition(inputElement, caretPosition);
            	}

            	// Make an AJAX request to fetch data based on refNumber
            	const refNumber = inputElement.value;
            	if (refNumber.trim() !== '') {
                	fetchUserData(refNumber);
            	}
        	}

        	function fetchUserData(refNumber) {
            	// Make an AJAX request to your server
            	$.ajax({
                	type: 'POST',
                	url: 'fetch_patient_data.php', // Update this with the actual server-side script
                	data: { refNumber: refNumber },
                	success: function (data) {
                    	console.log('Data received:', data);

                    	// Parse the returned JSON data
                    	const userData = JSON.parse(data);

                    	// Update the form fields with the fetched data
                    	document.getElementById('patient_name').value = userData.patient_name;
                    	document.getElementById('age').value = userData.age;
                    	document.getElementById('gender').value = userData.gender;
                	},
                	error: function (xhr, status, error) {
                    	console.error('Error fetching user data:', error);
                	}
            	});
        	}

        	function setCaretPosition(element, position) {
            	if (element.setSelectionRange) {
                	element.focus();
                	element.setSelectionRange(position, position);
            	} else if (element.createTextRange) {
                	const range = element.createTextRange();
                	range.collapse(true);
                	range.moveEnd('character', position);
                	range.moveStart('character', position);
                	range.select();
            	}
        	}
	

