
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

$('#bt_healthkroomba').on('click', function () {
	$('#md_modal').dialog({title: "{{Santé K Roomba}}"});
	$('#md_modal').load('index.php?v=d&plugin=kroomba&modal=health').dialog('open');
});


$("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});

function addCmdToTable(_cmd) {
    if (!isset(_cmd)) {
        var _cmd = {configuration: {}};
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }
      var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
      tr += '<td>';
      tr += '<span class="cmdAttr" data-l1key="id"></span>';
      tr += '</td><td>';
      tr += '<div class="row">';
      tr += '<div class="col-sm-2">';
      tr += '<a class="cmdAction btn btn-default btn-sm" data-l1key="chooseIcon"><i class="fas fa-flag"></i> Icône</a>';
      tr += '<span class="cmdAttr" data-l1key="display" data-l2key="icon" style="margin-left : 10px;"></span>';
      tr += '</div>';
      tr += '<div class="col-sm-4">';
      tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" style="width : 140px;" placeholder="{{Nom}}">';
      tr += '</div>';
      tr += '</div>';
      tr += '</td><td>';
      tr += '<input class="cmdAttr form-control input-sm" data-l1key="type" disabled>';
      tr += '<input class="cmdAttr form-control input-sm" data-l1key="subType" style="display : none;">';
      tr += '</td><td>';
      tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
      tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" />{{Afficher}}</label></span> ';
      tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="display" data-l2key="invertBinary"/>{{Inverser}}</label></span> ';
      tr += '</td><td>';
      if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Tester}}</a>';
      }
      tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
      tr += '</tr>';
      $('#table_cmd tbody').append(tr);
      $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
      if (isset(_cmd.type)) {
          $('#table_cmd tbody tr:last .cmdAttr[data-l1key=type]').value(init(_cmd.type));
      }
      jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
}

$('#md_modal_kroomba').dialog({
  autoOpen: false,
  buttons: {
    "{{Continuer}}": function() {
      $( this ).dialog( "close" );
      $.ajax({// fonction permettant de faire de l'ajax
          type: "POST", // methode de transmission des données au fichier php
          url: "plugins/kroomba/core/ajax/kroomba.ajax.php", // url du fichier php
          data: {
              action: "getPassword",
              ip:$('#roomba_ip_input').value(),
              blid:$('#username_input').value(),
          },
          dataType: 'json',
          error: function (request, status, error) {
              handleAjaxError(request, status, error);
          },
          success: function (data) { // si l'appel a bien fonctionné
              if (data.state != 'ok') {
                  $('#div_alert').showAlert({message: data.result, level: 'danger'});
                  return;
              }
              $('#password_input').value(data.result);
              $('#div_alert').showAlert({message: '{{Synchronisation réussie. Pensez à sauvegarder !}}', level: 'success'});
          }
      });
    },
    "{{Annuler}}": function() {
      $( this ).dialog( "close" );
    }
  }
});

$('#bt_getPassword').on('click', function () {
  $('#md_modal_kroomba').dialog('open');
  return false;
});