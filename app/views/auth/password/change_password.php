{% extends 'templates/default.php' %}

{% block title %} Change Password {% endblock %}

{% block content %}
  <div class="container">
    <div class="row flex-column align-items-center ">
      
    {% for key, error in errors %}
      {% if errors[key] %}
        {% if key == 'invalid_params' or key == 'param_not_found' or key == 'refresh' %}
          <div class="col-md-6">
            <p class="alert alert-danger">{{ error }}</p>
          </div>
        {% endif %}
      {% endif%}
    {% endfor %}
      
      <form action="{{ url_for('password.change.post') }}" method="post" autocomplete="off" class="col-md-6">
        <fieldset class="text-capitalize">
          <legend class="h1">Change Password</legend>
          <div class="form-group mb-2 row">
            <label for="old_password" class="col-form-label col-md-4 d-none d-md-block">old password</label>
            <div class="col-md-8">
              <input type="password" name="old_password" id="old_password" class="form-control" placeholder="Old Password" autofocus required>
              {% if errors.old_password %}
                <small class="form-text invalid-feedback d-block">{{ errors.old_password }}</small>
              {% endif %}
            </div>
          </div>

          <div class="form group row mb-2">
            <label for="new_password" class="col-md-4 col-form-label d-none d-md-block">New Password</label>
            <div class="col-md-8">
              <input type="password" name="new_password" id="new_password" class="col-md-8 form-control" placeholder="New Password" required>
              {% if errors.new_password %}
                {% for error in errors.new_password %}
                  <small class="d-block form-text invalid-feedback">- {{ error }}</small>
                {% endfor %}
              {% endif %}
            </div>
          </div>

          <div class="form-group row mb-2">
            <label class="col-form-label col-md-4 d-none d-md-block">Confirm new password</label>
            <div class="col-md-8">
              <input type="password" name="confirm_new_password" id="confirm_new_password" class="col-md-8 form-control" placeholder="Confirm new password" required>
              {% if errors.new_password %}
                {% for error in errors.confirm_new_password %}
                  <small class="d-block form-text invalid-feedback">- {{ error }}</small>
                {% endfor %}
              {% endif %}
            </div>
          </div>

          <input type="hidden" name="{{ csrf.key }}" value="{{ csrf.token }}">
          <input type="hidden" name="recaptcha-response" id="recaptchaResponse">

          <div class="form-group mt-5">
            <input type="submit" value="Change" class="btn btn-lg btn-primary text-capitalize">
          </div>
          
        </fieldset>
      </form>
      
    </div>
  </div>



  <!-- Recaptcha -->
  {% include 'templates/partials/_recaptcha.php' %}
{% endblock %}