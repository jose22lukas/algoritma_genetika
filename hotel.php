
  <title>Bootstrap Theme The Band</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
  body {
    font: 400 15px/1.8 Lato, sans-serif;
    color: #777;
  }
  h3, h4 {
    margin: 10px 0 30px 0;
    letter-spacing: 10px;      
    font-size: 20px;
    color: #111;
  }
  .container {
    padding: 80px 120px;
  }
  .person {
    border: 10px solid transparent;
    margin-bottom: 25px;
    width: 80%;
    height: 80%;
    opacity: 0.7;
  }
  .person:hover {
    border-color: #f1f1f1;
  }
  
  .bg-1 {
    background-image: url("http://localhost/dinas/dinas/assets/img/2.jpeg");
    color: #bdbdbd;
  }
  .bg-1 h3 {color: #fff;}
  .bg-1 p {font-style: italic;}
  
  .thumbnail {
    padding: 0 0 15px 0;
    border: none;
    border-radius: 0;
  }
  .thumbnail p {
    margin-top: 15px;
    color: #555;
  }
  .btn {
    padding: 10px 20px;
    background-color: #333;
    color: #f1f1f1;
    border-radius: 0;
    transition: .2s;
  }
  .btn:hover, .btn:focus {
    border: 1px solid #333;
    background-color: #fff;
    color: #000;
  }
  .nav-tabs li a {
    color: #777;
  }
  #googleMap {
    width: 100%;
    height: 400px;
    -webkit-filter: grayscale(100%);
    filter: grayscale(100%);
  }  
  .navbar {
    font-family: Montserrat, sans-serif;
    margin-bottom: 0;
    background-color: #2d2d30;
    border: 0;
    font-size: 11px !important;
    letter-spacing: 4px;
    opacity: 0.9;
  }
  .navbar li a, .navbar .navbar-brand { 
    color: #d5d5d5 !important;
  }
  .navbar-nav li a:hover {
    color: #fff !important;
  }
  .navbar-nav li.active a {
    color: #fff !important;
    background-color: #29292c !important;
  }
  .navbar-default .navbar-toggle {
    border-color: transparent;
  }
  .open .dropdown-toggle {
    color: #fff;
    background-color: #555 !important;
  }
  .dropdown-menu li a {
    color: #000 !important;
  }
  .dropdown-menu li a:hover {
    background-color: red !important;
  }
  footer {
    background-color: #2d2d30;
    color: #f5f5f5;
    padding: 32px;
  }
  footer a {
    color: #f5f5f5;
  }
  footer a:hover {
    color: #777;
    text-decoration: none;
  }  
  .form-control {
    border-radius: 0;
  }
  textarea {
    resize: none;
  }
  </style>
</head>
<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="50">

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="www.inka.co.id"><img src="https://upload.wikimedia.org/wikipedia/commons/f/f4/Logo_INKA_-_Industri_Kereta_Api_Indonesia.svg" alt="" width="100" height="30"></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="<?= base_url('enduser') ?>"><span class="fa fa-home"></span></a></li>
        <li><a href="<?= base_url('endriwayat') ?>"><span class="fa fa-history"></a></li>
        <li><a href="#tour"><span class="fa fa-bell"></a></li>
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-user"></i>
          <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?= base_url('endprofile') ?>"><i class="fa fa-fw fa-user"></i> Profile</a></li>
            <li><a href="<?= base_url('login/logout') ?>"><i class="fa fa-fw fa-power-off"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>


<!-- Container (TOUR Section) -->

<div id="tour" class="bg-1">
  <div class="container">
    <h3 class="text-center">PILIH HOTEL</h3>
    <div class="card">
      <div class="card-body">
       

          <table class="table table-bordered" style="margin-bottom: 10px; color:white;">
            <tr>
              <th>No</th>    
              <th>Nama Hotel</th>
              <th>Kota</th>
              <th>Check In</th>
              <th>Check Out</th>
              <th>Jumlah Hari</th>
              <th>Harga</th>
              <th>Total</th>
              <th>Bintang</th>
              <th>Aksi</th>
           </tr>
           <?php foreach($hotel_reko_view as $row_hotel){ ?>
            <tr>
              <th><?php echo 1; ?></th>
              <th><?php echo $row_hotel->hotelName?></th>
              <th><?php echo $row_hotel->location->name?></th>
              <th><?php echo $checkin?></th>
              <th><?php echo $checkout?></th>
              <th><?php echo $lama?></th>
              <th><?php echo $row_hotel->priceAvg / $lama?></th>
              <th><?php echo $row_hotel->priceAvg?></th>
              <th><?php echo $row_hotel->stars?></th>
              <th>
              <form action="http://localhost/dinas/dinas/Enduser/pilih/" method="post">
                <input type="hidden" name="nama_hotel" value="<?= $row_hotel->hotelName ?>">
                <input type="hidden" name="location" value="<?= $row_hotel->location->name ?>">
                <input type="hidden" name="tanggal" value="<?= $checkin ?>">
                <input type="hidden" name="lama" value="<?= $lama ?>">
                <input type="hidden" name="harga" value="<?= $row_hotel->priceAvg / $lama ?>">
                <input type="hidden" name="total" value="<?= $row_hotel->priceAvg ?>">
                <input type="hidden" name="stars" value="<?= $row_hotel->stars ?>">
                <input type="submit" name="submit" value="Pilih">
              </form>
              </th>
          </tr>
           <?php  } ?>
        </table>   

      </div>
    </div>

  
</div>


<!-- Footer -->
<footer class="text-center"><br>
  <p><a href="https://www.inka.co.id" data-toggle="tooltip" title="Visit INKA">INKA Copyright &copy; 2019</a></p> 
</footer>

<script>
$(document).ready(function(){
  // Initialize Tooltip
  $('[data-toggle="tooltip"]').tooltip(); 
  
  // Add smooth scrolling to all links in navbar + footer link
  $(".navbar a, footer a[href='#myPage']").on('click', function(event) {

    // Make sure this.hash has a value before overriding default behavior
    if (this.hash !== "") {

      // Prevent default anchor click behavior
      event.preventDefault();

      // Store hash
      var hash = this.hash;

      // Using jQuery's animate() method to add smooth page scroll
      // The optional number (900) specifies the number of milliseconds it takes to scroll to the specified area
      $('html, body').animate({
        scrollTop: $(hash).offset().top
      }, 900, function(){
   
        // Add hash (#) to URL when done scrolling (default click behavior)
        window.location.hash = hash;
      });
    } // End if
  });
})
</script>

</body>
</html>