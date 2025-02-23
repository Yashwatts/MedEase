 function moveFocus(verticalDirection, horizontalDirection) {
            const formFields = document.querySelectorAll('input, textarea, select');
            const currentFocus = document.activeElement;
            let currentIndex = Array.from(formFields).indexOf(currentFocus);

            if (currentIndex !== -1) {
                const numRows = 4; // Change this based on the number of rows in your form
                const numCols = 4; // Change this based on the number of columns in your form

                // Calculate the next index in a serial manner
                const nextIndex = currentIndex + verticalDirection * numCols + horizontalDirection;

                // Ensure the next index is within bounds
                if (nextIndex >= 0 && nextIndex < formFields.length) {
                    formFields[nextIndex].focus();
                }
            }
        }
 

   
  function populateDistricts() {
    var divisionSelect = document.getElementById("division");
    var districtSelect = document.getElementById("district");

    // Clear previous options
    districtSelect.innerHTML = '<option value="" disabled selected>Select District</option>';

    // Get selected division
    var selectedDivision = divisionSelect.value;

    // Add districts based on the selected division
    switch (selectedDivision) {
        case "dhaka":
            districtSelect.innerHTML += '<option value="dhaka">Dhaka</option>';
            districtSelect.innerHTML += '<option value="gazipur">Gazipur</option>';
            districtSelect.innerHTML += '<option value="narayanganj">Narayanganj</option>';
            districtSelect.innerHTML += '<option value="kishoreganj">Kishoreganj</option>';
            districtSelect.innerHTML += '<option value="manikganj">Manikganj</option>';
            districtSelect.innerHTML += '<option value="munshiganj">Munshiganj</option>';
            districtSelect.innerHTML += '<option value="narsingdi">Narsingdi</option>';
            districtSelect.innerHTML += '<option value="rajbari">Rajbari</option>';
            districtSelect.innerHTML += '<option value="shariatpur">Shariatpur</option>';
            districtSelect.innerHTML += '<option value="faridpur">Faridpur</option>';
            districtSelect.innerHTML += '<option value="madaripur">Madaripur</option>';
            districtSelect.innerHTML += '<option value="gopalganj">Gopalganj</option>';
            break;

        case "barisal":
            districtSelect.innerHTML += '<option value="barisal">Barisal</option>';
            districtSelect.innerHTML += '<option value="bhola">Bhola</option>';
            districtSelect.innerHTML += '<option value="patuakhali">Patuakhali</option>';
            districtSelect.innerHTML += '<option value="barguna">Barguna</option>';
            districtSelect.innerHTML += '<option value="jhalokathi">Jhalokathi</option>';
            districtSelect.innerHTML += '<option value="pirojpur">Pirojpur</option>';
            break;

        case "chittagong":
            districtSelect.innerHTML += '<option value="chittagong">Chittagong</option>';
            districtSelect.innerHTML += '<option value="coxs_bazar">Coxs Bazar</option>';
            districtSelect.innerHTML += '<option value="comilla">Comilla</option>';
            districtSelect.innerHTML += '<option value="feni">Feni</option>';
            districtSelect.innerHTML += '<option value="brahmanbaria">Brahmanbaria</option>';
            districtSelect.innerHTML += '<option value="chandpur">Chandpur</option>';
            districtSelect.innerHTML += '<option value="noakhali">Noakhali</option>';
            districtSelect.innerHTML += '<option value="laxmipur">Laxmipur</option>';
            districtSelect.innerHTML += '<option value="khagrachari">Khagrachari</option>';
            districtSelect.innerHTML += '<option value="bandarban">Bandarban</option>';
            break;

        case "khulna":
            districtSelect.innerHTML += '<option value="khulna">Khulna</option>';
            districtSelect.innerHTML += '<option value="bagerhat">Bagerhat</option>';
            districtSelect.innerHTML += '<option value="chuadanga">Chuadanga</option>';
            districtSelect.innerHTML += '<option value="jessore">Jessore</option>';
            districtSelect.innerHTML += '<option value="jhenaidah">Jhenaidah</option>';
            districtSelect.innerHTML += '<option value="sathkhira">Sathkhira</option>';
            districtSelect.innerHTML += '<option value="magura">Magura</option>';
            districtSelect.innerHTML += '<option value="narail">Narail</option>';
            districtSelect.innerHTML += '<option value="kushtia">Kushtia</option>';
            districtSelect.innerHTML += '<option value="meherpur">Meherpur</option>';
            break;

        case "rajshahi":
            districtSelect.innerHTML += '<option value="rajshahi">Rajshahi</option>';
            districtSelect.innerHTML += '<option value="bogura">Bogura</option>';
            districtSelect.innerHTML += '<option value="joypurhat">Joypurhat</option>';
            districtSelect.innerHTML += '<option value="pabna">Pabna</option>';
            districtSelect.innerHTML += '<option value="chapainawabganj">Chapainawabganj</option>';
            districtSelect.innerHTML += '<option value="naogaon">Naogaon</option>';
            districtSelect.innerHTML += '<option value="natore">Natore</option>';
            districtSelect.innerHTML += '<option value="sirajganj">Sirajganj</option>';
            break;

        case "sylhet":
            districtSelect.innerHTML += '<option value="sylhet">Sylhet</option>';
            districtSelect.innerHTML += '<option value="habiganj">Habiganj</option>';
            districtSelect.innerHTML += '<option value="moulvibazar">Moulvibazar</option>';
            districtSelect.innerHTML += '<option value="sunamganj">Sunamganj</option>';
            break;

        case "rangpur":
            districtSelect.innerHTML += '<option value="rangpur">Rangpur</option>';
            districtSelect.innerHTML += '<option value="gaibandha">Gaibandha</option>';
            districtSelect.innerHTML += '<option value="nilphamari">Nilphamari</option>';
            districtSelect.innerHTML += '<option value="kurigram">Kurigram</option>';
            districtSelect.innerHTML += '<option value="lalmonirhat">Lalmonirhat</option>';
            districtSelect.innerHTML += '<option value="dinajpur">Dinajpur</option>';
            districtSelect.innerHTML += '<option value="thakurgaon">Thakurgaon</option>';
            districtSelect.innerHTML += '<option value="panchagarh">Panchagarh</option>';
            break;

        case "mymensingh":
            districtSelect.innerHTML += '<option value="mymensingh">Mymensingh</option>';
            districtSelect.innerHTML += '<option value="netrakona">Netrakona</option>';
            districtSelect.innerHTML += '<option value="jamalpur">Jamalpur</option>';
            districtSelect.innerHTML += '<option value="tangail">Tangail</option>';
            districtSelect.innerHTML += '<option value="sherpur">Sherpur</option>';
            break;
    }
}





    function generateRandomNumber() {
    var randomSuffix = Array.from({ length: 4 }, () => Math.floor(Math.random() * 10)).join('');
    var refNumber = "1619840" + randomSuffix;
    document.getElementById('refNumber').value = refNumber;
}

// Invoke the function when the page loads
window.onload = generateRandomNumber;



        function updatePhone() {
            var emergencyContact = document.getElementById('emergency_contact').value;
            document.getElementById('phone').value = emergencyContact; // Update phone field with emergency contact value
        }