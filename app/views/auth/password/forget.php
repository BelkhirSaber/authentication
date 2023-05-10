{% extends 'templates/default.php' %}

{% block title %} Forget password {% endblock %}

{% block content %}
  <div class="container">
    <div class="row flex-column align-items-center">

      {% if errors.user_not_found %}
        <div class="col-md-6">
          <p class="alert alert-danger">{{ errors.user_not_found }}</p>
        </div>
      {% endif %}

      <div class="col-md-6">
        <form action="{{ url_for('password.forget.post') }}" method="post" autocomplete="off">
          <fieldset class="text-capitalize">
            <legend class="h2">Forget password</legend>
            <div class="form-group row mb-2">
              <label class="col-form-label d-none d-md-block col-auto">user email</label>
              <div class="col-md-8">
                <input class="form-control" type="email" name="user_email" value="{% if request.user_email %} {{request.user_email}} {% endif %}" placeholder="Enter your email" autofocus required>
              </div>
            </div>
            <input type="hidden" name="{{ csrf.key }}" value="{{ csrf.token }}">
            <input type="hidden" name="recaptcha-response" id="recaptchaResponse">
          
            <div class="form-group mt-5">
              <input type="submit" value="send code" class="btn btn-primary text-capitalize">
            </div>
          </fieldset>
        </form>
      </div>

    </div>
  </div>


  <!-- Recaptcha -->
  {% include 'templates/partials/_recaptcha.php' %}
{% endblock %}