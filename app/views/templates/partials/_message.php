{% if flash.global %}
  <div class="global d-none">{{ flash.global }}</div>
{% endif %}

{% if flash.success %}
  <div class="success d-none">{{ flash.success }}</div>
{% endif %}

{% if flash.error %}
  <div class="error d-none">{{ flash.error }}</div>
{% endif %}

