{% extends 'email/templates/default.php'%}

{% block content %}
  <p>Notification: your account password changed!</p>
  <p>Date/Time: {{"now"|date('y:m:d H:i:s')}}</p>
  <p>Best regards,</p>
  <small>Technic Support</small>
{% endblock %}