{% extends 'email/templates/default.php'%}

{% block content %}
  <p>Please click to reset password link and fill the instruction for reset your password</p>
  <div><a class="btn btn-primary" href="{{baseUrl}}/reset-password?email={{email}}&token={{hash|url_encode}}">reset password</a></div>
  <p>Date/Time: {{"now"|date('y:m:d H:i:s')}}</p>
  <p>Best regards,</p>
  <small>Technic Support</small>
{% endblock %}