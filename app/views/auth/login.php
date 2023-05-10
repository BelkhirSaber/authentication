{% extends 'templates/default.php' %}

{% block title %} Login {% endblock %}

{% block content %}

<div class="container">
  <div class="row align-items-center flex-column">
  
    {% if errors.fails %}
      <div class="col-md-6 alert alert-danger">
        {{ errors.fails }}
      </div>
    {% endif %}

    <div class="col-md-6">
      <form action="{{url_for('login.post')}}" method="post">
        <fieldset>
          <legend class="h1">Login</legend>
          <div class="form-group row mb-2">
            <label class="col-md-4 col-form-label d-none d-md-block" for="identifier">Username</label>
            <div class="col-md-8">
              <input class="form-control" type="text" name="identifier" value = "{% if request.identifier %}{{request.identifier}}{% endif %}" id="identifier" placeholder="Enter Username Or Email" autofocus required/>
              {% if errors['identifier.identifier'] %}
                <small class="form-text invalid-feedback d-block" id="identifierHelp">{{ errors['identifier.identifier'] }}</small>
              {% endif %}
              {% if errors['identifier.required'] %}
                <small class="form-text invalid-feedback d-block" id="identifierHelp">{{ errors['identifier.required'] }}</small>
              {% endif %}
            </div>
          </div>

          <div class="form-group row mb-2">
            <label class="col-md-4 col-form-label d-none d-md-block" for="password">Password</label>
            <div class="col-md-8">
              <input class="form-control" type="password" name="password" id="password" placeholder="Enter Password" autocomplete="new-password" required/>
              {% if errors['password.required'] %}
                <small class="form-text d-none invalid-feedback" id="passwordHelp">{{ errors['password.required'] }}</small>
              {% endif %}
            </div>
          </div>

          <div class="from-check mt-3">
            <input class="from-check-input" type="checkbox" name="remember_me" id="remember_me">
            <label class="form-check-label form-text" for="remember_me">Remember me</label>
          </div>

          <input type="hidden" name="{{ csrf.key }}" value="{{ csrf.token }}">
          <input type="hidden" name="recaptcha-response" id="recaptchaResponse">

          <div class="form-group row mt-5">
            <div class="col-md-4">
              <input class="form-control btn btn-primary" type="submit" value="login"/>
            </div>

            <div class="col-md-4 ms-auto text-end">
              <a href="{{ url_for('password.forget') }}">Forget password</a>
            </div>
          </div>
          

        </fieldset>
      </form>

      <p class="mt-5 text-capitalize">if not have any account please click <a href="{{url_for('register')}}">create a account</a></p>
    </div>
  </div>
</div>

  <!-- Recaptcha -->
  {% include 'templates/partials/_recaptcha.php' %}


{% endblock %}