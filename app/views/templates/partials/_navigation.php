<header class="mb-5">
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary py-1">
    <div class="container-fluid">
      <a class="navbar-brand" href="{{ url_for('home')}}">Login system</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      
      <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item">
            <a class="nav-link active" href="{{ url_for('home') }}">Home
              <span class="visually-hidden">(current)</span>
            </a>
          </li>
          {% if auth %}
            {% if auth.isAdmin() %}
              <li class="nav-item">
                <a class="nav-link active" href="{{ url_for('all_user') }}">All User
                  <span class="visually-hidden">(current)</span>
                </a>
              </li>
            {% endif %}

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <img class="img-thumbnail rounded-circle" src="{{ auth.getAvatar({size: 25}) }}" alt="profile avatar"/>
                <span>{{ auth.getUsernameOrFullName() }}</span>
              </a>
              <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="{{ url_for('profile', {username: auth.username}) }}">Profile</a>
                <a class="dropdown-item" href="{{ url_for('password.change') }}">Change Password</a>
                <a class="dropdown-item" href="{{ url_for('logout') }}">Logout</a>
              </div>
            </li>
          {% else %}
            <li class="nav-item">
              <a class="nav-link" href="{{ url_for('login') }}">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ url_for('register') }}">Register</a>
            </li>
          {% endif %}

        </ul>
      </div>
    </div>
  </nav>
</header>