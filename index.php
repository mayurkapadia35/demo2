<?php
$conn=mysqli_connect("localhost","root","","dbtest");

if(!$conn)
{
    die("Connection Failed".mysqli_connect_error());
}

$sql="select * from tblstate";
$res=mysqli_query($conn,$sql);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Index!!!</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
<table align="center">
    <tr>
        <td>First Name</td>
        <td><input type="text" id="fname" placeholder="First Name" required></td>
    </tr>

    <tr>
        <td>Last Name</td>
        <td><input type="text" id="lname" placeholder="Last Name" required></td>
    </tr>

    <tr>
        <td>Email</td>
        <td><input type="email" id="email" placeholder="Email" required></td>
    </tr>

    <tr>
        <td>Select a State</td>
        <td><select id="selectstate">
                <option value="">-- Select a State --</option>
                <?php
                if(mysqli_num_rows($res)>0)
                {
                    while ($row=mysqli_fetch_array($res))
                    {
                        ?>
                        <option value="<?php echo $row[0]; ?>"><?php echo $row[1]; ?></option>
                        <?php
                    }
                }
                ?>
            </select></td>
    </tr>

    <tr>
        <td>Select City: </td>
        <td><select id="selectcity">
                <option name="">--Select City--</option>
            </select></td>
    </tr>

    <tr>
        <td></td>
        <td><input type="button" id="btnsave" value="Save">
            <input type="reset" name="reset" value="Reset"></td>
    </tr>
</table>
<br>

<table id="tbldisplay">
    <thead>
        <th>did</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>State</th>
        <th>City</th>
    </thead>
    <tbody>

    </tbody>
</table>
</body>
</html>

<script>
    $(document).ready(function () {
        $("select#selectstate").change(function () {
            var sid=this.value;
            $.ajax({
                type:"get",
                url: "http://localhost:3001/city?id="+sid,
                success:function (data) {
                    $("select#selectcity").empty();
                    $("select#selectcity").append("<option value=''>"+ '-- Select City --' +"</option>");
                    for (var x in data)
                    {
                        $("select#selectcity").append("<option value='"+data[x].cid+"'>"+ data[x].cname +"</option>");
                    }
                }
            });

        });

        $("#btnsave").click(function () {
            var fname=document.getElementById("fname").value;
            var lname=document.getElementById("lname").value;
            var email=document.getElementById("email").value;
            var state=document.getElementById("selectstate").value;
            var city=document.getElementById("selectcity").value;

            // var data={
            //     "firstname": fname,
            //     lastname: lname,
            //     email: email,
            //     state: state,
            //     city: city
            // }
            //alert(data.firstname);
            $.ajax({
               type: "POST",
               url: "http://localhost:3001/insert",
                data: {fname: fname,lname: lname,email: email,state: state,city: city},
                success:function (data) {
                    alert("record inserted");
                }
            });
        });

        window.onload=function () {

            var c=[];

            $.ajax({
               type: "GET",
                url: "http://localhost:3001/display",
                success: function (data) {
                   var len=data.length;
                    for (var x in data)
                    {
                        c="<tr><td>"+data[x].did+"</td>";
                        c+="<td>"+data[x].firstname+"</td>";
                        c+="<td>"+data[x].lastname+"</td>";
                        c+="<td>"+data[x].email+"</td>";
                        c+="<td>"+data[x].state+"</td>";
                        c+="<td>"+data[x].city+"</td></tr>";
                        console.log(data[x]);
                        //alert(x);
                    }
                    $("#tbldisplay tbody").html(c);
                }
            });
        };
    });
</script>