<%@page contentType="text/plain; charset=UTF-8" %>
<%@page import="java.io.*"%>
<%@page import="protocol.*"%>
<%@include file="WEB-INF/.pwadminconf.jsp"%>
<%
    // Same as rolexml.jsp process=save: XmlRole.fromXML → putRoleToDB
    String remote = request.getRemoteAddr();
    if (!"127.0.0.1".equals(remote) && !"0:0:0:0:0:0:0:1".equals(remote) && !"::1".equals(remote)) {
        response.setStatus(403);
        out.print("FORBIDDEN");
        return;
    }
    if (!"POST".equals(request.getMethod())) {
        response.setStatus(405);
        out.print("METHOD_NOT_ALLOWED");
        return;
    }
    String token = request.getParameter("token");
    if (token == null || !token.equals("pw_panel_sync_2026")) {
        response.setStatus(403);
        out.print("FORBIDDEN");
        return;
    }
    String ident = request.getParameter("ident");
    String xml = request.getParameter("xml");
    if (ident == null || xml == null) {
        response.setStatus(400);
        out.print("MISSING_PARAM");
        return;
    }
    int id;
    try {
        id = Integer.parseInt(ident);
    } catch (Exception e) {
        response.setStatus(400);
        out.print("BAD_ID");
        return;
    }
    try {
        XmlRole.Role role = XmlRole.fromXML(xml.getBytes("UTF-8"));
        role.base.id = id;
        XmlRole.putRoleToDB(id, role);
        out.print("OK");
    } catch (Exception e) {
        response.setStatus(500);
        out.print("ERROR: " + (e.getMessage() != null ? e.getMessage() : e.getClass().getName()));
    }
%>
