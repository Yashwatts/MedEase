// script.js

// A function to populate the doctors dropdown based on the selected department
function populateDoctors() {
    // Get the selected department value
    const department = document.getElementById('department').value;
    const doctorSelect = document.getElementById('doctor');

    // Clear any existing options
    doctorSelect.innerHTML = '<option value="" disabled selected>Select Doctor</option>';

    // Define doctors for each department (dummy data)
    const doctors = {
        cardiology: ['Dr. Smith', 'Dr. Jones'],
        dermatology: ['Dr. Brown', 'Dr. Taylor'],
        orthopedics: ['Dr. Wilson', 'Dr. Moore'],
        neurology: ['Dr. White', 'Dr. Harris'],
        oncology: ['Dr. Clark', 'Dr. Lewis'],
        pediatrics: ['Dr. Robinson', 'Dr. Walker'],
        gastroenterology: ['Dr. Hall', 'Dr. Young'],
        opthamology: ['Dr. Allen', 'Dr. Scott'],
        urology: ['Dr. King', 'Dr. Wright']
    };

    // Populate the doctor options based on the selected department
    if (department && doctors[department]) {
        doctors[department].forEach(doctor => {
            const option = document.createElement('option');
            option.value = doctor;
            option.text = doctor;
            doctorSelect.add(option);
        });
    } else {
        // Add a placeholder option if no department is selected
        const placeholderOption = document.createElement('option');
        placeholderOption.value = '';
        placeholderOption.disabled = true;
        placeholderOption.selected = true;
        placeholderOption.text = 'Select Doctor';
        doctorSelect.add(placeholderOption);
    }
}
