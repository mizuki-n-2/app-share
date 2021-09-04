$(function () {
  
  get_data();

  $('#default').on('click', function () {
    if ($(this).prop("checked"))
      $("#image").val(null);
  });
});

function get_data() {
  $.ajax({
    url: "/ajax",
    dataType: "json",
    success: data => {
      if (data.notifications.length !== 0) {
        $('#notification-icon').css('color','#FFCC00');
      }
    },
    error: () => {
      console.error("ajax Error");
    }
  });

  setTimeout("get_data()", 5000);
}