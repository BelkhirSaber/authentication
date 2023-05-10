<script src="https://www.google.com/recaptcha/enterprise.js?render=6LeC2-klAAAAAKva_bvQRn6ZI6LAUvLWOPwftkir"></script>
<script>
  grecaptcha.enterprise.ready(function() {
    grecaptcha.enterprise.execute('6LeC2-klAAAAAKva_bvQRn6ZI6LAUvLWOPwftkir', {action: 'submit'}).then(function(token) {
      document.getElementById('recaptchaResponse').value = token;
    });
  });
</script>