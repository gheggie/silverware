<% if $Content %>
  <% if $Component.ContentShown($IsFirst) %>
    <div class="content">
      $Content
    </div>
  <% end_if %>
<% end_if %>