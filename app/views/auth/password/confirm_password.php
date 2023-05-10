{% extends 'templates/default.php' %}

{% block title %} Confirm password {% endblock %}

{% block content %}
  <div class="container">
    <div class="row flex-column align-items-center">
  
      {% for key, error in errors %}
        {% if errors[key] %}
          <div class="col-md-8">
            <p class="alert alert-danger">{{ error }}</p>
          </div>
        {% endif %}
      {% endfor %}
      <div class="col-md-8">
        <form action="{{ url_for('password.confirm.post') }}" method="post" autocomplete="off" >
          <fieldset>
            <legend class="h3">Confirmation change password</legend>
            <div class="form-group row mb-2">
              <div class="col-md-6">
                <input type="text" name="confirm_code" class="form-control" placeholder="Enter confirmation code"/>
              </div>
              {% if errors.confirm_code %}
                <small class="d-block form-text invalid-feedback">{{ errors.confirm_code }}</small>
              {% endif %}  
            </div>

            <input type="hidden" name="{{ csrf.key }}" value="{{csrf.token}}" />
            <input type="hidden" name="recaptcha-response" id="recaptchaResponse">

            <div class="mt-5">
              <input type="submit" value="send" class="col-md-3 btn btn-primary">
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </div>


  <!-- Recaptcha -->
  {% include 'templates/partials/_recaptcha.php' %}
{% endblock %}