$(function () {
  get_data();
});

function get_data() {
  $.ajax({
    url: "/ajax",
    dataType: "json",
    success: data => {
      // console.log(data);

      var count = data.notifications.length;

      document.querySelector('#notification-count').textContent = count;
    },
    error: () => {
      alert("ajax Error");
    }
  });

  setTimeout("get_data()", 5000);
}