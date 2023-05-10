{% if auth %}
  <p>Dear {{auth.getUsernameOrFullName()}},</p>
{% else %}
  <p>Hello There,</p>
{% endif %}

{% block content %}
  
{% endblock %}