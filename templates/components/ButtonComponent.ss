<% if $EnabledButtons %>
  <ul class="$ListClass">
    <% loop $EnabledButtons %>
      <li>$Me</li>
    <% end_loop %>
  </ul>
<% end_if %>