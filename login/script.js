function loadSignUp() {
    $('#container').addClass('hidden-content');
    $.ajax({
        url: 'signup.php',
        type: 'GET',
        success: function(response) {
            $('#signupModal .modal-body').html(response);
            $('#signupModal').modal('show');
        },
        error: function(xhr, status, error) {
            console.error('Error loading signup.php:', error);
        }
    });
}

$('#loginForm').submit(function(e) {
    e.preventDefault();
    var formData = $(this).serialize();
    
    $.ajax({
        url: 'login.php',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            console.log('Response:', response);
            if (response.success) {
                switch (response.role) {
                    case 'admin':
                        window.location.href = '../admin/index.php';
                        break;

                    case 'doctor':
                        window.location.href = '../doctor/index.php';
                        break;

                    case 'patient':
                        window.location.href = '../patient/index.php';
                        break;

                    case 'employee':
                        window.location.href = '../employee/index.php';
                        break;

                    case 'account_branch':
                        window.location.href = '../account/index.php';
                        break;

                    default:
                        alert('Invalid role');
                }
            } else {
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        }
    });
});

function validateLoginForm(){
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    if (username.trim()==="") {
        alert("Please Enter Your Correct Username");
        return;
    }
    if (password.trim()==="") {
        alert("Please Enter Your Correct Password");
        return;
    }
    document.getElementById("loginForm").submit();
}

$('#signupForm').submit(function(e) {
    e.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: 'signup.php',
        type: 'POST',
        data: formData,
        success: function(response) {
            console.log('Response:', response);  // Log the entire response
            if (response.includes('Registration Successful')) {
                alert('Registration Successful');
            } else if (response.includes('Error:')) {
                alert(response);  // Show the actual error response
            } else {
                alert('Registration failed');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        }
    });
});