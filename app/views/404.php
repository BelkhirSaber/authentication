{% extends 'templates/default.php' %}

{% block title %} Page not found {% endblock %}

{% block content %}
  <div class="container">
    <h3>Your requested page not found !</h3>
    
    <a href="{{ url_for('home') }}">Go To Home</a>
  </div>
{% endblock %}