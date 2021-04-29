<% if $Component.MetaShown($IsFirst) %>
  <div class="meta">
    <% if $HasMetaDate %>
      <span class="date">
        <% if $Component.ShowDateIcon %><i class="fa fa-fw fa-calendar"></i><% end_if %>
        $MetaDate.Format($Component.DateFormat)
      </span>
    <% end_if %>
  </div>
<% end_if %>