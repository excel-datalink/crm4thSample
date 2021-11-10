/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**********************************!*\
  !*** ./resources/js/this_app.js ***!
  \**********************************/
$(function () {
  var DETAIL_INDEX_VARIABLE = -1; // delete row

  $('.delete-row-link').click(function () {
    var ret = window.confirm('この行を削除しますか?');

    if (ret === false) {
      return;
    } // clear inputs


    $(this).closest('tr').find('input').each(function (idx, e) {
      if ($(e).attr('type') !== 'hidden') {
        $(e).val('');
      }
    }); // set delete flag

    $(this).next('input').val(1); // invisible row

    $(this).closest('tr').hide();
  }); // delete row hide

  $('.delete-flag').each(function (idx, e) {
    if ($(e).val() !== '') {
      $(e).closest('tr').hide();
    }
  }); // add row

  $('#add-detail').click(function () {
    // clone template row tag
    var row = $('#template-row-field').clone(true);
    var raw_html = row.html(); // attach new detail id

    raw_html = raw_html.replace(/_INDEX_VARIABLE/g, DETAIL_INDEX_VARIABLE);
    DETAIL_INDEX_VARIABLE--;
    row.html(raw_html); // remove template id and attributes

    row.attr('id', '');
    row.show();
    row.find('.delete-flag').first().val(''); // increments append row counts

    $('#row-counts').val(Number($('#row-counts').val()) + 1); // attach event

    row.find('.detail-quantity').change(calc_price);
    row.find('.detail-unit-price').change(calc_price);
    row.find('.detail-quantity').change(calc_total_price);
    row.find('.detail-unit-price').change(calc_total_price);
    row.find('.detail-price').change(calc_total_price); // append to details

    $('#detail-table-tbody').append(row);
  });

  var calc_price = function calc_price() {
    var row = $(this).closest('tr');
    var quantity = row.find('.detail-quantity').first();
    var unit_price = row.find('.detail-unit-price').first();
    var price = row.find('.detail-price').first();
    var p = Number(quantity.val()) * Number(unit_price.val());
    price.val(p);
  };

  var calc_total_price = function calc_total_price() {
    var total_price = 0;
    $('.detail-price').each(function (i, e) {
      total_price += Number($(e).val());
    });
    var tax_rate = $('#tax_rate').val();
    total_price *= 1 + tax_rate / 100;
    $('#total_price').val(total_price);
  }; // calc price


  $('.detail-quantity').change(calc_price);
  $('.detail-unit-price').change(calc_price); // calc total price

  $('.detail-quantity').change(calc_total_price);
  $('.detail-unit-price').change(calc_total_price);
  $('.detail-price').change(calc_total_price);
  $('#tax_rate').change(calc_total_price); // print estimate

  $('#print-btn').click(function () {
    $('#estimate-report-frame')[0].contentWindow.print();
  });
});
/******/ })()
;