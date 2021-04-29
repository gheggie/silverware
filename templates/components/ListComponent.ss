<% if $ListItems %>
  <div class="$WrapperClass">
    $RenderListItems
  </div>
  <% if $PaginateItems %>
    <% include Pagination List=$ListItems %>
  <% end_if %>
<% else %>
  <div class="error">
    <p><i class="fa fa-fw fa-warning"></i> <% _t('ListComponent_ss.NODATAAVAILABLE', 'No data available.') %></p>
  </div>
<% end_if %>