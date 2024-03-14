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
            color: #fff;
            text-align: center;
        }
        .content {
            padding: 20px;
            line-height: 1.6;
        }
        .logo {
            width: 150px;
            height: 150px;
           
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
        .Name-and-Date{
            display:flex;
            flex-direction:row;
            justify-content:space-between;
        }
        .spacer {
        margin-left: 200px;
    }
    .schoolName{
        font-size:30px;
        color: black;
        font-weight: bold;
    }

    .location{
        font-size:25px;
        color: black;
    }
    .theText{
        font-size:16px;
        color: black; 
    }

   

    </style>
</head>
<body>
    <div class="header">
        <img src="{{storage_path('app/public/'.$CompanyLogo) }}" alt="School Logo" class="logo">
       
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

        <div class="location">Appointment Letter </div>

       </div>

       
      <hr/>

      <p class="theText">1. We take great pleasure in extending to you an official appointment as a distinguished {PrimaryRole} at <b>{{$CompanyName}}</b>. Your profound expertise and unwavering commitment to the field of {PrimaryRole} render you a valuable asset to our esteemed <b>{{$CompanyName}}</b>, and it is with genuine enthusiasm that we welcome you to our esteemed educational community.</p>
      <p class="theText">2. Your esteemed role within our institution shall be that of {Teacher.Position}, accompanied by the responsibilities outlined in the attached document detailing your professional obligations. We firmly believe that your extensive experience and commendable skill set will significantly enrich the educational journey of our students and further enhance the overall achievements of our esteemed institution.</p>
      <p class="theText">3. Your official tenure commences on {Teacher.StartDate}. Kindly ensure your punctual presence at {Teacher.ReportingTime} at the school premises each day, in accordance with our established schedule.</p>
      <p class="theText">4. In recognition of your appointment, you will be entitled to an initial salary of {Teacher.Salary}. Comprehensive details pertaining to your remuneration package, inclusive of benefits and the specifics of your employment terms, will be thoughtfully presented to you on the inaugural day of your service at our institution.</p>
     <p class="theText">5. Your staff identification number is <b>{{$TheUserId}}</b>. This is the number you will use throughout your stay at our school. Your password is <b>{{$Password}}</b>; please be sure not to misplace this information.</p>
      <p class="theText">6. Should you require any clarifications or seek additional information, please do not hesitate to reach out to our Human Resources department. We are genuinely excited to have you join our esteemed team, and we eagerly anticipate our collaborative efforts in the provision of outstanding education to our cherished students.</p>
  
      
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
