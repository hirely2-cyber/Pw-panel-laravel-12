<%@page contentType="application/json; charset=UTF-8" %>
<%@page import="java.util.*" %>
<%@page import="protocol.*" %>
<%@page import="com.goldhuman.IO.Protocol.Rpc.Data.DataVector" %>
<%@include file="WEB-INF/.pwadminconf.jsp"%>
<%
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

    int roleId = 0;
    int userId = 0;

    try {
        String ident = request.getParameter("ident");
        String uid = request.getParameter("userid");

        if (ident != null && ident.length() > 0) {
            roleId = Integer.parseInt(ident);
        } else if (uid != null && uid.length() > 0) {
            userId = Integer.parseInt(uid);
        } else {
            response.setStatus(400);
            out.print("{\"ok\":false,\"message\":\"ident or userid required\"}");
            return;
        }

        RoleBean character = null;

        if (roleId > 0) {
            character = GameDB.get(roleId);
        } else {
            DataVector dv = GameDB.getRolelist(userId);
            if (dv != null && dv.size() > 0) {
                IntOctets ios = (IntOctets) dv.get(0);
                roleId = ios.m_int;
                character = GameDB.get(roleId);
            }
        }

        if (character == null || character.user == null) {
            response.setStatus(404);
            out.print("{\"ok\":false,\"message\":\"character not found\"}");
            return;
        }

        int resolvedUserId = character.base != null ? character.base.userid : 0;

        StringBuilder json = new StringBuilder();
        json.append("{\"ok\":true");
        json.append(",\"roleid\":").append(roleId);
        json.append(",\"userid\":").append(resolvedUserId);
        json.append(",\"logicuid\":").append(character.user.logicuid);
        json.append(",\"cash\":").append(character.user.cash);
        json.append(",\"cash_add\":").append(character.user.cash_add);
        json.append(",\"cash_buy\":").append(character.user.cash_buy);
        json.append(",\"cash_sell\":").append(character.user.cash_sell);
        json.append(",\"cash_used\":").append(character.user.cash_used);
        json.append("}");

        out.print(json.toString());
    } catch (NumberFormatException e) {
        response.setStatus(400);
        out.print("{\"ok\":false,\"message\":\"invalid ident/userid\"}");
    } catch (Exception e) {
        response.setStatus(500);
        String msg = e.getMessage();
        if (msg == null) msg = "internal error";
        msg = msg.replace("\"", "'");
        out.print("{\"ok\":false,\"message\":\"" + msg + "\"}");
    }
%>
