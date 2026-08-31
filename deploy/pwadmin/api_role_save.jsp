<%@page contentType="application/json; charset=UTF-8" %>
<%@page import="java.util.*" %>
<%@page import="protocol.*" %>
<%@include file="WEB-INF/.pwadminconf.jsp"%>
<%
    // ── Security: localhost only + token ──────────────────────
    String remote = request.getRemoteAddr();
    if (!"127.0.0.1".equals(remote) && !"0:0:0:0:0:0:0:1".equals(remote) && !"::1".equals(remote)) {
        response.setStatus(403);
        out.print("{\"ok\":false,\"message\":\"Forbidden\"}");
        return;
    }

    String token = request.getParameter("token");
    if (token == null || !token.equals("pw_panel_sync_2026")) {
        response.setStatus(403);
        out.print("{\"ok\":false,\"message\":\"Invalid token\"}");
        return;
    }

    // ── Resolve role ──────────────────────────────────────────
    int roleId = 0;
    try {
        String ident = request.getParameter("ident");
        if (ident == null || ident.length() == 0) {
            response.setStatus(400);
            out.print("{\"ok\":false,\"message\":\"ident (roleid) required\"}");
            return;
        }
        roleId = Integer.parseInt(ident);
    } catch (NumberFormatException e) {
        response.setStatus(400);
        out.print("{\"ok\":false,\"message\":\"invalid ident\"}");
        return;
    }

    RoleBean character = null;
    try {
        character = GameDB.get(roleId);
    } catch (Exception e) {
        response.setStatus(500);
        out.print("{\"ok\":false,\"message\":\"GameDB.get failed: " + safe(e.getMessage()) + "\"}");
        return;
    }

    if (character == null) {
        response.setStatus(404);
        out.print("{\"ok\":false,\"message\":\"character not found\"}");
        return;
    }

    // ── Apply only fields that are present in request ─────────
    StringBuilder applied = new StringBuilder();
    StringBuilder errors  = new StringBuilder();
    boolean changed = false;

    String p;

    // world (status.worldtag)
    p = request.getParameter("world");
    if (p != null && p.length() > 0) {
        try {
            character.status.worldtag = Integer.parseInt(p.trim());
            appendField(applied, "world");
            changed = true;
        } catch (Exception e) { appendField(errors, "world"); }
    }

    // coordinateX (status.posx)
    p = request.getParameter("coordinateX");
    if (p != null && p.length() > 0) {
        try {
            character.status.posx = Float.valueOf(p.trim()).floatValue();
            appendField(applied, "coordinateX");
            changed = true;
        } catch (Exception e) { appendField(errors, "coordinateX"); }
    }

    // coordinateZ (status.posz)
    p = request.getParameter("coordinateZ");
    if (p != null && p.length() > 0) {
        try {
            character.status.posz = Float.valueOf(p.trim()).floatValue();
            appendField(applied, "coordinateZ");
            changed = true;
        } catch (Exception e) { appendField(errors, "coordinateZ"); }
    }

    // coordinateY (status.posy)
    p = request.getParameter("coordinateY");
    if (p != null && p.length() > 0) {
        try {
            character.status.posy = Float.valueOf(p.trim()).floatValue();
            appendField(applied, "coordinateY");
            changed = true;
        } catch (Exception e) { appendField(errors, "coordinateY"); }
    }

    // reputation (status.reputation)
    p = request.getParameter("reputation");
    if (p != null && p.length() > 0) {
        try {
            character.status.reputation = Integer.parseInt(p.trim());
            appendField(applied, "reputation");
            changed = true;
        } catch (Exception e) { appendField(errors, "reputation"); }
    }

    // exp (status.exp)
    p = request.getParameter("exp");
    if (p != null && p.length() > 0) {
        try {
            character.status.exp = Integer.parseInt(p.trim());
            appendField(applied, "exp");
            changed = true;
        } catch (Exception e) { appendField(errors, "exp"); }
    }

    // sp (status.sp)
    p = request.getParameter("sp");
    if (p != null && p.length() > 0) {
        try {
            character.status.sp = Integer.parseInt(p.trim());
            appendField(applied, "sp");
            changed = true;
        } catch (Exception e) { appendField(errors, "sp"); }
    }

    // cultivation (status.level2)
    p = request.getParameter("cultivation");
    if (p != null && p.length() > 0) {
        try {
            character.status.level2 = Integer.parseInt(p.trim());
            appendField(applied, "cultivation");
            changed = true;
        } catch (Exception e) { appendField(errors, "cultivation"); }
    }

    // vigor (ep.max_ap)
    p = request.getParameter("vigor");
    if (p != null && p.length() > 0) {
        try {
            character.ep.max_ap = Integer.parseInt(p.trim());
            appendField(applied, "vigor");
            changed = true;
        } catch (Exception e) { appendField(errors, "vigor"); }
    }

    // pocketcoins (pocket.money)
    p = request.getParameter("pocketcoins");
    if (p != null && p.length() > 0) {
        try {
            int v = Integer.parseInt(p.trim());
            if (v > 200000000) v = 200000000;
            if (v < 0) v = 0;
            character.pocket.money = v;
            appendField(applied, "pocketcoins");
            changed = true;
        } catch (Exception e) { appendField(errors, "pocketcoins"); }
    }

    // storehousecoins (storehouse.money)
    p = request.getParameter("storehousecoins");
    if (p != null && p.length() > 0) {
        try {
            int v = Integer.parseInt(p.trim());
            if (v > 200000000) v = 200000000;
            if (v < 0) v = 0;
            character.storehouse.money = v;
            appendField(applied, "storehousecoins");
            changed = true;
        } catch (Exception e) { appendField(errors, "storehousecoins"); }
    }

    if (!changed) {
        out.print("{\"ok\":false,\"message\":\"no fields provided\"}");
        return;
    }

    // ── Persist to GameDB ─────────────────────────────────────
    try {
        GameDB.update(character);
    } catch (Exception e) {
        response.setStatus(500);
        out.print("{\"ok\":false,\"message\":\"GameDB.update failed: " + safe(e.getMessage()) + "\"}");
        return;
    }

    StringBuilder json = new StringBuilder();
    json.append("{\"ok\":true");
    json.append(",\"roleid\":").append(roleId);
    json.append(",\"applied\":[").append(applied).append("]");
    if (errors.length() > 0) {
        json.append(",\"errors\":[").append(errors).append("]");
    }
    json.append("}");
    out.print(json.toString());
%>
<%!
    private static void appendField(StringBuilder sb, String name) {
        if (sb.length() > 0) sb.append(",");
        sb.append("\"").append(name).append("\"");
    }
    private static String safe(String s) {
        if (s == null) return "";
        return s.replace("\\", "\\\\").replace("\"", "\\\"");
    }
%>
