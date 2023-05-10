{% extends 'email/templates/default.php'%}

{% block content %}
  <p>Confirmation code for reset password</p>
  <h3>{{ confirm_code }}</h3>
  <p>Date/Time: {{"now"|date('y:m:d H:i:s')}}</p>
  <p>Best regards,</p>
  <small>Technic Support</small>
{% endblock %}