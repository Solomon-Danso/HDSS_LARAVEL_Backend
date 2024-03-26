<!DOCTYPE html>
<html>
<head>
    <title>Admission Letter</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            color: #000; /* Changed text color to black */
            text-align: center;
            position: relative; /* Added for watermark positioning */
        }
        .content {
            padding: 20px;
            line-height: 1.6;
        }
        .logo {
    width: 100%;
    height: 100%;
    opacity: 0.2; /* Adjust the opacity for watermark effect */
    position: fixed; /* Changed to fixed for covering the entire page */
    top: 0;
    left: 0;
    z-index: -1; /* Ensure logo stays behind text */
}
        .student-logo {
            width: 100px;
            height: auto;
            display: block;
            margin: 20px auto;
        }
        .profile-pic {
            width: 200px;
            height: 200px;
            margin: 20px auto;
            display: block;
        }
        .Name-and-Date {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }
        .spacer {
            margin-left: 200px;
        }
        .schoolName {
            font-size: 30px;
            font-weight: bold;
        }
        .location {
            font-size: 25px;
        }
        .theText {
            font-size: 16px;
        }
    </style>

</head>
<body>
    <div class="header">
        <img src="{{ storage_path('app/public/' . $CompanyLogo) }}" alt="School Logo" class="logo">
    </div>

    <div class="content">

        <div >
        <span class="theText">Dear {{ $UserName }},</span>
        <span class="spacer">{{$currentDate}}</span>
       </div>

       <br/>

       <div class="header">
        <div class="schoolName">{{$CompanyName}}</div>
        <div class="location">{{$Location}} </div>

        <div class="location">Admission Letter </div>

       </div>

       
      <hr/>


        <p class="theText">1. We are delighted to extend an offer of admission to <b>{{$CompanyName}}</b> for this academic year. It brings us great pleasure to welcome you to our school community, and we are excited about the prospect of having you as a student in our school.</p>
        <p  class="theText">2. Your application to <b>{{$CompanyName}}</b> was thoroughly reviewed by our admissions committee, and we were impressed by your academic potential, your eagerness to learn, and the positive qualities you exhibited during the admission process. Your enthusiasm for education and your readiness to engage with our school curriculum make you an excellent fit for our school.</p>
        <p  class="theText">3. Your student identification number is <b>{{$TheUserId}}</b>. This is the number you will use throughout your studies at our school. Your password is <b>{{$Password}}</b>; please be sure not to misplace this information.</p>
        <p  class="theText">4. For the {student.TheAcademicYear} academic year, the Admission Fee is {adfee?.Amount}, and the Tuition Fee, along with other fees, for {student.Level} {student.TheAcademicTerm} is {otherfee}.</p>
        <p  class="theText">5. Your total provisional fees for {student.Level} {student.TheAcademicTerm} in the {student.TheAcademicYear} academic year amount to {fees}. Please make this payment to the school's accountant only.</p>
         <p  class="theText">6. If you have any questions or require further assistance, please feel free to contact our admissions office. We look forward to having you join our <b>{{$CompanyName}}</b> family and embark on an exciting educational adventure. We are confident that your time with us will be filled with learning, growth, and lasting friendships.</p>
         <p  class="theText">7. Congratulations once again on your admission to <b>{{$CompanyName}}</b>. We can't wait to get to know you better and watch you thrive as a member of our school community.</p>
        <p class="theText">Yours faithfully,</p>
       <br/>
       <p class="theText">................................</p>
        <b>{{$TheSenderName}}</b><br/>
       <i> {{$SenderPosition}} </i>
       
    </div>
    <div class="header">
        <img src="{{storage_path('app/public/'.$ProfilePic) }}" alt="Profile Picture" class="profile-pic">
    </div>
</body>
</html>
