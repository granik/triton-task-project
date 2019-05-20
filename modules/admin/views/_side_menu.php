<div class="col-md-3 pull-left border p-1">
       <div id="accordion">
            <div class="card">
              <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                  <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <strong>Пользователи</strong>
                  </button>
                </h5>
              </div>

              <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    <ul class="list-unstyled">
                       <li><a href="/admin/users">Список пользователей</a></li>
                       <li><a href="/admin/users/create">Новый пользователь</a></li>
                    </ul>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header" id="headingTwo">
                <h5 class="mb-0">
                  <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                      <strong>Исходные данные</strong>
                  </button>
                </h5>
              </div>
              <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body">
                    <ul class="list-unstyled">
                       <li><a href="/admin/city">Города</a></li>
                       <li><a href="/admin/category">Категории</a></li>
                       <li><a href="/admin/event-type">Типы событий</a></li>
                       <li><a href="/admin/sponsor-type">Типы спонсоров</a></li>
                    </ul>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header" id="headingThree">
                <h5 class="mb-0">
                  <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    <strong>Поля таблиц</strong>
                  </button>
                </h5>
              </div>
              <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                <div class="card-body">
                    <ul class="list-unstyled">
                       <li><a class="menu-item-admin" href="/admin/fields/info">Основное инфо</a></li>
                       <li><a class="menu-item-admin" href="/admin/fields/logistics">Логистическое инфо</a></li>
                       <li><a class="menu-item-admin" href="/admin/fields/finance">Финансовое инфо</a></li>
                       <li><a class="menu-item-admin" href="/admin/fields/webinars">Вебинары инфо</a></li>
                    </ul>
                </div>
              </div>
            </div>
        </div>
    </div>