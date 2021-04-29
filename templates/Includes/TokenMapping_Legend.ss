<% if $Mappings %>
  <div class="field token-mapping-legend">
    <% if $Title %>
      <h2>$Title</h2>
    <% end_if %>
    <dl class="token-mappings">
      <% loop $Mappings %>
        <dt>$Token</dt>
        <dd>$Description</dd>
      <% end_loop %>
    </dl>
  </div>
<% end_if %>
