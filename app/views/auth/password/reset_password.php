{% extends 'templates/default.php' %}

{% block title %} Reset Password {% endblock %}

{% block content %}
  <div class="container">
    <div class="row flex-column align-items-center ">
      
    {% if errors %}
      <div class="col-md-6">
        <p class="alert alert-danger">
          {% for error in errors %}
            <span class="d-block">- {{ error }}</span>
          {% endfor %}
        </p>
      </div>
    {% endif %}
      
      <form action="{{ url_for('password.reset.post') }}{{ query }}" method="post" autocomplete="off" class="col-md-6">
        <fieldset class="text-capitalize">
          <legend class="h1">Reset Password</legend>

          <div class="form group row mb-2">
            <label for="new_password" class="col-md-4 col-form-label d-none d-md-block">New Password</label>
            <div class="col-md-8">
              <input type="password" name="new_password" id="new_password" class="col-md-8 form-control" placeholder="New Password" required>
            </div>
          </div>

          <div class="form-group row mb-2">
            <label class="col-form-label col-md-4 d-none d-md-block">Confirm new password</label>
            <div class="col-md-8">
              <input type="password" name="confirm_new_password" id="confirm_new_password" class="col-md-8 form-control" placeholder="Confirm new password" required>
            </div>
          </div>

          <input type="hidden" name="{{ csrf.key }}" value="{{ csrf.token }}">
          <input type="hidden" name="recaptcha-response" id="recaptchaResponse">

          <div class="form-group mt-5">
            <input type="submit" value="Change" class="btn btn-lg btn-primary text-capitalize">
          </div>
          
        </fieldset>
      </form>
      
    </>
  </div>



  <!-- Recaptcha -->
  {% include 'templates/partials/_recaptcha.php' %}
{% endblock %}