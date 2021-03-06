<?php

if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('kroomba');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());

?>

<div class="row row-overflow">
  <div class="col-xs-12 eqLogicThumbnailDisplay">
    <legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
    <div class="eqLogicThumbnailContainer">
      <div class="cursor eqLogicAction logoPrimary" data-action="add">
        <i class="fas fa-plus-circle"></i>
        <br>
        <span>{{Ajouter}}</span>
      </div>
      <div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
        <i class="fas fa-wrench"></i>
        <br>
        <span>{{Configuration}}</span>
      </div>
      <div class="cursor logoSecondary" id="bt_healthkroomba">
        <i class="fas fa-medkit"></i>
        <br />
        <span>{{Santé}}</span>
      </div>
    </div>
    <legend><i class="fas fa-table"></i> {{Mes Roombas}}</legend>
    <input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
    <div class="eqLogicThumbnailContainer">
        <?php
        foreach ($eqLogics as $eqLogic) {
            $opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
            echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
            echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
            echo '<br>';
            echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
            echo '</div>';
        }
        ?>
    </div>
  </div>


  <div class="col-xs-12 eqLogic" style="display: none;">
    <div class="input-group pull-right" style="display:inline-flex">
      <span class="input-group-btn">
        <a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a>
        <a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
        <a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
      </span>
    </div>
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
      <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
      <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Commandes}}</a></li>
    </ul>
    <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
      <div role="tabpanel" class="tab-pane active" id="eqlogictab">
        <br/>
        <form class="form-horizontal">
          <fieldset>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{Nom}}</label>
              <div class="col-sm-3">
                <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom du Roomba}}"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label" >{{Objet parent}}</label>
              <div class="col-sm-3">
                <select class="form-control eqLogicAttr" data-l1key="object_id">
                  <option value="">{{Aucun}}</option>
                  <?php
                  foreach (jeeObject::all() as $object) {
                    echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{Catégorie}}</label>
              <div class="col-sm-8">
                <?php
                foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                  echo '<label class="checkbox-inline">';
                  echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                  echo '</label>';
                }
                ?>

              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label"></label>
              <div class="col-sm-8">
                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">{{Commentaire}}</label>
              <div class="col-sm-3">
                <textarea class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="commentaire" ></textarea>
              </div>
            </div>

            <div id="roomba_ip" class="form-group">
              <label class="col-sm-3 control-label">{{Adresse IP du Roomba}}</label>
              <div class="col-sm-3">
                <input type="text" class="eqLogicAttr configuration form-control" id="roomba_ip_input" data-l1key="configuration" data-l2key="roomba_ip" placeholder="exemple 192.168.0.77"/>
              </div>
            </div>

            <div id="username" class="form-group">
              <label class="col-sm-3 control-label">{{Identifiant du Roomba}}</label>
              <div class="col-sm-3">
                <input type="text" class="eqLogicAttr configuration form-control" id="username_input" data-l1key="configuration" data-l2key="username"/>
              </div>
            </div>

            <div id="password" class="form-group">
              <label class="col-sm-3 control-label">{{Mot de passe}}</label>
              <div class="col-sm-3">
                <input type="password" class="eqLogicAttr configuration form-control" id="password_input" data-l1key="configuration" data-l2key="password"/>
              </div>
              <div class="col-lg-2">
                <a class="btn btn-info bt_getPassword" id="bt_getPassword"><i class='fas fa-qrcode'></i> {{Récupérer le mot de passe}}</a>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">{{Auto-actualisation (cron)}}</label>
              <div class="col-sm-3">
                <input type="checkbox" class="eqLogicAttr" data-label-text="{{Activer}}" data-l1key="configuration" data-l2key="cron_isEnable" checked/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label"></label>
              <div class="col-sm-3">
                <div class="input-group">
                  <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="autorefresh" placeholder="{{Auto-actualisation (cron)}}"/>
                  <span class="input-group-btn">
                      <a class="btn btn-default cursor jeeHelper" data-helper="cron">
                          <i class="fas fa-question-circle"></i>
                      </a>
                  </span>
                </div>
              </div>
            </div>
          </fieldset>
        </form>
      </div>
      <div role="tabpanel" class="tab-pane" id="commandtab">
        <table id="table_cmd" class="table table-bordered table-condensed">
          <thead>
            <tr>
              <th style="width: 50px;">#</th>
              <th>{{Nom}}</th>
              <th style="width: 100px;">{{Type}}</th>
              <th style="width: 300px;">{{Options}}</th>
              <th style="width: 150px;">{{Action}}</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div id="md_modal_kroomba" title="{{Récupérer le mot de passe}}">
    <p>
      <span class="glyphicon glyphicon-warning-sign" style="float:left; margin:12px 12px 20px 0;"></span>
      {{Premièrement : assurez-vous qu'aucune application mobile iRobot est connectée au Roomba.}}<br />
      {{Assurez-vous que votre roomba est sur sa base et alimenté (LED vertes allumées).}}<br />
      {{Puis restez appuyé sur le bouton HOME jusqu'à ce que votre roomba joue une série de sons (environ 2 secondes).}}<br />
      {{Pour les Roomba 960, il faut rester appuyé à la fois sur HOME et SPOT.}}<br />
      {{Relachez le bouton, votre roomba va faire clignoter son voyant WIFI.}}<br />
      {{Puis appuyez sur "Continuer".}}
    </p>
  </div>

</div>

<?php include_file('desktop', 'kroomba', 'js', 'kroomba'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>
