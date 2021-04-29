<% if $HasFontIcon %>
  <i class="$FontIconClass"<% if $FontIconColorCSS %> style="color: {$FontIconColorCSS}"<% end_if %>></i>
<% else %>
  <span class="na"><% _t('FontIconTagCMS_ss.NOTAVAILABLE', 'N/A') %></span>
<% end_if %>