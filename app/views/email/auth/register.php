{% extends 'email/templates/default.php' %}

{% block content %}
  <p>Please confirm your email registration to access our platform. Click the link in the email we sent you to complete the process. Thank you!</p>
  <p>{{base_url}}{{ url_for('activate')  }}?email={{user.email}}&identifier={{active_identifier|url_encode}}</p>
  <p>Best regards,</p>
  <small>Technic Support</small>
{% endblock %}