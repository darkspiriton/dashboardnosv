<li class="dropdown a-pointer">
    <a data-toggle="dropdown" class="tm-notification"><i class="tmn-counts" ng-bind="notifications.length"></i></a>
    <div class="dropdown-menu dropdown-menu-lg pull-right">
        <div class="listview" id="notifications">
            <div class="lv-header">
                Notificaciones

                <ul class="actions">
                    <li class="dropdown">
                        <a ng-click="clearNotification()">
                            <i class="md md-done-all"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="lv-body c-overflow">
                <a class="lv-item" ng-repeat="notification in notifications">
                    <div class="media">
                        <div class="pull-left">
                            <img class="lv-img-sm" ng-src="img/check@{{ notification.status }}.jpg" alt="">
                        </div>
                        <div class="media-body">
                            <div class="lv-title" ng-bind="notification.title"></div>
                            <small class="lv-small ws-normal" ng-bind="notification.body"></small>
                            <small class="lv-small" ng-bind="formatDate(notification.created_at) | date:'dd-MM-yyyy HH:mm:ss'"></small>
                        </div>
                        <ul class="actions pull-right">
                            <li class="dropdown">
                                <div ng-click="checkNotification($index)">
                                    <i class="md md-done-all"></i>
                                </div>
                            </li>
                        </ul>
                    </div>
                </a>
            </div>

            <a class="lv-footer" ng-bind="(notifications.length || 0) + ' Notificaciones'"></a>
        </div>

    </div>
</li>