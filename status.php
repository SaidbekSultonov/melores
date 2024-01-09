
  <link rel="stylesheet" href="Flip/css/flipTimer.css" />
  <style>
    body {
      background-color: #b8d6df;
    }
    .flipTimer {
      margin: 100px auto 0;
      width: 500px;
    }
  </style>

  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
  <script src="Flip/js/jquery.flipTimer.js"></script>

  
  <div class="flipTimer">
    <div class="hours"></div>
    <div class="minutes"></div>
    <div class="seconds"></div>
  </div>
  <script>
    $(document).ready(function() {
      var date2 = new Date();
      date2.setDate(date2.getDate() + 1);
      $('.flipTimer').flipTimer({ direction: 'up', date: date2 });
    });
  </script>
  
   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
  <script src="Flip/js/jquery.flipTimer.js"></script>
