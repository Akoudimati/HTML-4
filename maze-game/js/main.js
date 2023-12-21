function emailSend() {
    var userName = document.getElementById('name').value;
    var userEmail = document.getElementById('email').value;
    var phone = document.getElementById('phone').value; // Assuming you have a phone field
    var subject = document.getElementById('subject').value;
    var feedback = document.getElementById('feedback').value;
 
    var messageBody =
       "Name " + userName +
       "<br/> Phone " + phone +
       "<br/> Email " + userEmail +
       "<br/> Subject " + subject +
       "<br/> Message " + feedback;
 
    Email.send({
       Host: "smtp.elasticemail.com",
       Username: "abdullahkoudimati99@gmail.com",
       Password: "5113525Mmk///",
       To: 'abdullhkoudimati@gmail.com',
       From: "abdullahkoudimati99@gmail.com",
       Subject: "This is the subject",
       Body: messageBody
    }).then(
       message => {
          if (message == 'OK') {
             swal("Successful", "You clicked the button!", "success");
          } else {
             swal("Error", "You clicked the button!", "error");
          }
       }
    );
 }
 document.getElementById('contactForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevents the default form submission
    emailSend();
 });
 
 