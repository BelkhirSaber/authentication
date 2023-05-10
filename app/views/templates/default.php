<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Webiste | {% block title %} {% endblock %}</title>
  <!-- Bootswatch Materia -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/5.2.3/materia/bootstrap.min.css" integrity="sha512-BHK6ttFyaq4IZg5NXAhqbkjOGo5fA4HMhOK9UA9MsYCGWp7QCxv1zZKNrNNzezh0jeQxk+FRC5TeKMxUrrTzrQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Fontawesome solid-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Css Alertify-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.min.css" integrity="sha512-IXuoq1aFd2wXs4NqGskwX2Vb+I8UJ+tGJEu/Dc0zwLNKeQ7CW3Sr6v0yU3z5OQWe3eScVIkER4J9L7byrgR/fA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/themes/default.min.css" integrity="sha512-RgUjDpwjEDzAb7nkShizCCJ+QTSLIiJO1ldtuxzs0UIBRH4QpOjUU9w47AF9ZlviqV/dOFGWF6o7l3lttEFb6g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link rel="apple-touch-icon" sizes="180x180" href="{{baseUrl}}/assets/icons/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="{{baseUrl}}/assets/icons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="{{baseUrl}}/assets/icons/favicon-16x16.png">
  <link rel="manifest" href="{{baseUrl}}/assets/icons/site.webmanifest">
  {% block style %} 
  {% endblock %}
</head>
<body>
  {% include 'partials/_message.php' %}
  {% include 'partials/_navigation.php' %}
  {% block content %} {% endblock %}

  <!-- Js Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  <!-- Js Alertify -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js" integrity="sha512-JnjG+Wt53GspUQXQhc+c4j8SBERsgJAoHeehagKHlxQN+MtCCmFDghX9/AcbkkNRZptyZU4zC8utK59M5L45Iw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  {% if flash.global %}
    <script>
      let msg = document.getElementsByClassName('global').item(0).innerHTML;
      var notification = alertify.notify(msg, '', 5);
    </script>
  {% endif %}
  {% if flash.success %}
    <script>
      msg = document.getElementsByClassName('success').item(0).innerHTML;
      var notification = alertify.notify(msg, 'success', 10);
    </script>
  {% endif %}
  {% if flash.error %}
    <script>
      msg = document.getElementsByClassName('error').item(0).innerHTML;
      var notification = alertify.notify(msg, 'error', 5);
    </script>
  {% endif %}
  
  {% block script %} {% endblock %}
</body>
</html>