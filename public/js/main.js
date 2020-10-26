$(function () {
  
  get_data();

  $('#default').on('click', function () {
    $('#image').val(null);
  });
  
});

function get_data() {
  $.ajax({
    url: "/ajax",
    dataType: "json",
    success: data => {
      // console.log(data);

      if (data.notifications.length !== 0) {
        $('#notification-icon').css('color','#FFCC00');
      }
    },
    error: () => {
      alert("ajax Error");
    }
  });

  setTimeout("get_data()", 5000);
}