{% extends 'email/templates/default.php' %}

{% block content %}
  <p>Please confirm your change password to access our platform. Click the link in the email we sent you to complete the process. Thank you!</p>
  <h4>Confirmation code</h4>
  <h2>{{code}}</h2>
  <p>Best regards,</p>
  <small>Technic Support</small>
{% endblock %}