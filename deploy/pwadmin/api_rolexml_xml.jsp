<%@page contentType="text/xml; charset=UTF-8" %>
<%@page import="java.io.*"%>
<%@page import="protocol.*"%>
<%@include file="WEB-INF/.pwadminconf.jsp"%>
<%
    // Same access model as api_sync_roles.jsp: localhost + shared token
    String remote = request.getRemoteAddr();
    if (!"127.0.0.1".equals(remote) && !"0:0:0:0:0:0:0:1".equals(remote) && !"::1".equals(remote)) {
        response.setStatus(403);
        return;
    }
    String token = request.getParameter("token");
    if (token == null || !token.equals("pw_panel_sync_2026")) {
        response.setStatus(403);
        return;
    }
    String ident = request.getParameter("ident");
    if (ident == null) {
        response.setStatus(400);
        return;
    }
    int id;
    try {
        id = Integer.parseInt(ident);
    } catch (Exception e) {
        response.setStatus(400);
        return;
    }
    try {
        String xml;
        if (id > 15) {
            xml = new String(XmlRole.toXMLByteArray(XmlRole.getRoleFromDB(id)), "UTF-8");
        } else {
            XmlRole.Role role = new XmlRole.Role();
            role.base = new GRoleBase();
            role.status = new GRoleStatus();
            role.pocket = new GRolePocket();
            role.equipment = new GRoleEquipment();
            role.storehouse = new GRoleStorehouse();
            role.task = new GRoleTask();
            if (role.task == null) {
                role.task = new GRoleTask();
            }
            xml = new String(XmlRole.toXMLByteArray(role), "");
        }
        out.print(xml);
    } catch (Exception e) {
        response.setStatus(500);
    }
%>
