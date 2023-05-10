{% extends 'templates/default.php' %}

{% block title %} Register {% endblock %}

{% block content %}
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="alert alert-danger h5 d-none" id ="error"></div>
        <form action="{{ url_for('register.post') }}" method="post" autocomplete="off" class="text-capitalize" id="register">
          <fieldset>
            <legend class="h1">Register</legend>
            <div class="form-group row mb-2">
              <label class="col-md-4 col-form-label d-none d-md-block" for="email">email</label>
              <div class="col-md-8">
                <input class="form-control" type="email" name="email" id="email" placeholder="Enter email" autofocus required/>
                <small class="form-text d-none invalid-feedback" id="emailHelp"></small>
              </div>
            </div>

            <div class="form-group row mb-2">
              <label class="col-md-4 col-form-label d-none d-md-block" for="username">username</label>
              <div class="col-md-8">
                <input class="form-control" type="text" name="username" id="username" placeholder="Enter username" required/>
                <small class="form-text d-none invalid-feedback" id="usernameHelp"></small>
              </div>
            </div>

            <div class="form-group row mb-2">
              <label class="col-md-4 col-form-label d-none d-md-block" for="password">password</label>
              <div class="col-md-8">
                <input class="form-control" type="password" name="password" id="password" placeholder="Enter password" oninput="strongPass(this.value)" autocomplete="new-password" minlength="8" required/>
                <small class="form-text d-none invalid-feedback" id="passwordHelp"></small>
                <ul class="p-0 ms-4 d-none text-muted">
                  <li><small id="lowercase">minimum one lowercase letter</small></li>
                  <li><small id="uppercase">minimum one uppercase letter</small></li>
                  <li><small id="numbers">minimum one numbers</small></li>
                  <li><small id="specialChar">minimum one special character</small></li>
                  <li><small id="length">minimum length 8 character without spaces " "</small></li>
                </ul>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-4 col-form-label d-none d-md-block" for="password_confirm">confirm password</label>
              <div class="col-md-8">
                <input class="form-control" type="password" name="password_confirm" id="password_confirm" placeholder="Confirm password" autocomplete="new-password" minlength="8" required/>
                <small class="form-text d-none invalid-feedback" id="password_confirmHelp"></small>
              </div>
            </div>

            <input type="hidden" name="{{ csrf.key }}" value="{{ csrf.token }}">

            <div class="col-md-3 form-group mt-5">
              <input class="form-control btn btn-primary" type="submit" value="register" onclick="user_exists(event)"/>
            </div>
          </fieldset>
        </form>

        <p class="mt-5 text-capitalize">if you have account please click <a href="{{url_for('login')}}">login</a></p>
      </div>
    </div>
  </div>

  
  <!-- Recaptcha -->
  {% include 'templates/partials/_recaptcha.php' %}
{% endblock %}

{% block script %}
  <script defer>

    let checker = document.getElementById('password').closest('div').getElementsByTagName('ul').item(0);
    password.addEventListener('focusin', ()=> checker.classList.remove('d-none') );

    regex = {
      length: /^[^\s]{8,}$/,
      lowercase: /[a-z]+/,
      uppercase: /[A-Z]+/,
      numbers: /[0-9]+/,
      specialChar: /[!@#$%^&*()_+\-=\[\]{};:'"\\|,.<>\/?]+/
    }

    function strongPass(value){
      for(key in regex){
        const small = document.getElementById(key);
        small.classList.remove('text-danger');
        if(value.match(regex[key])){
          small.classList.add('text-success');
        }else{
          small.classList.remove('text-success');
        }
      }
    }

    function user_exists(e){
      e.preventDefault();

      const form = document.forms.namedItem('register');
      const formData = new FormData(form);
      let jsonData = {};

      // Hide all errors message
      for(key of formData.keys()){
        if(key === 'csrf_token') {
          continue;
        }
        inputMessageHelp(key, true);
      }
      for(key of formData.keys()) jsonData[key] = formData.get(key);
      fieldExists(form, jsonData);
    }// end user_exists

    // Check input validity
    function inputValidator(input_name){
      return getInput(input_name).checkValidity();
    }

    // Get input from name
    function getInput(input_name){
      return document.getElementsByName(input_name).item(0);
    }

    /**
     * @param 
     * @param {Boolean}
     * @param {String}
     */

    function inputMessageHelp(key, valid = false, msg = ""){
      const small = document.getElementById(key+'Help');
      let results = {
        validity: 'is-invalid',
        message: msg != "" ? msg : getInput(key).validationMessage,
        removeClass: 'd-none',
        addClass: 'd-block',
      };

      if(valid){
        results.message = '';
        results.removeClass = 'd-block';
        results.addClass = 'd-none';
      }

      small.innerHTML = results.message;
      valid ? getInput(key).classList.remove(results.validity) : getInput(key).classList.add(results.validity); 
      small.classList.remove(results.removeClass);
      small.classList.add(results.addClass);
    }

    /**
     * Check if field exists in database
     * @param {Object}
     */
    function fieldExists(form, jsonData){
      const xHttp = new XMLHttpRequest();
      // Response XMLHttpRequest
      xHttp.onload = function(){
        const json = this.responseText != "" ? JSON.parse(this.responseText) : null;
        if(json != null){
          for (const key in json) {
            // Check if the number of params is identical
            if(key === "invalid_params_number"){
              let error = document.getElementById('error');
              error.classList.remove('d-none');
              error.innerHTML = json[key];
              continue;
            }
            // Check if this is password error
            const input_name = key.split('.')[0];
            if(input_name == "password" && !checker.classList.contains('d-none')){

              for(const id in regex){
                const small = document.getElementById(id);
                if(!small.className.includes('text-success')){
                  small.classList.add('text-danger')
                }
              }
              continue;
            }
            // Otherwise input check error
            inputMessageHelp(input_name, false, json[key]);
          }
          
        }else{
          form.submit();
        }
      }

      xHttp.open('POST', form.action.replace('register', 'check'));
      xHttp.setRequestHeader("Content-type", "application/json");
      xHttp.send(JSON.stringify(jsonData));
    }

  </script>
{% endblock %}