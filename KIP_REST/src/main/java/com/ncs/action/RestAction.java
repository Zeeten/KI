package com.ncs.action;

import java.io.InputStream;
import java.net.URL;
import java.util.ResourceBundle;
import java.util.Scanner;

import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.ResponseBody;
import org.springframework.web.bind.annotation.RestController;

@RestController
public class RestAction {

	private static ResourceBundle bundle = ResourceBundle
			.getBundle("com.ncs.bundle.ResourceBundle");

	@RequestMapping(value = "/rest/catalog", method = RequestMethod.GET, produces = "application/json; charset=UTF-8")
	public @ResponseBody String catalog() {
		InputStream inStream = null;
		URL u = null;
		try {
			u = new URL(bundle.getString("url")
					+ "/index.php?route=feed/rest_api/products&key=890");
			inStream = u.openStream();
		} catch (Exception e) {
			e.printStackTrace();
		}
		Scanner in = new Scanner(inStream);
		String str = null;
		while (in.hasNext()) {
			str = in.nextLine();
			str = str.replace("&nbsp;", " ");
			str = str.replaceAll("\\<[^>]*>", "");
		}
		in.close();
		return str;
	}

	@RequestMapping(value = "/rest/catalog/{id}", method = RequestMethod.GET)
	public @ResponseBody String getBook(@PathVariable int id) {
		InputStream inStream = null;
		URL u = null;
		try {
			u = new URL(bundle.getString("url") + "/index.php?id=" + id
					+ "&route=api/product");
			inStream = u.openStream();
		} catch (Exception e) {
			e.printStackTrace();
		}
		Scanner in = new Scanner(inStream);
		String str = null;
		while (in.hasNext()) {
			str = in.nextLine();
			str = str.replaceAll("\\<[^>]*>", "");
		}
		in.close();
		return str;
	}

	@RequestMapping(value = "/rest/login/{login}/{password}", method = RequestMethod.GET)
	public @ResponseBody String login(@PathVariable String login,
			@PathVariable String password) {
		InputStream inStream = null;
		URL u = null;
		try {
			u = new URL(bundle.getString("url") + "/index.php?email=" + login
					+ "&pwd=" + password + "&route=api/customerauth");
			inStream = u.openStream();
		} catch (Exception e) {
			e.printStackTrace();
		}
		Scanner in = new Scanner(inStream);
		String str = null;
		while (in.hasNext()) {
			str = in.nextLine();
		}
		in.close();
		return str;
	}

	@RequestMapping(value = "/rest/register/{firstname}/{lastname}/{email}/{password}", method = RequestMethod.GET)
	public @ResponseBody String register(@PathVariable String firstname,
			@PathVariable String lastname, @PathVariable String email,
			@PathVariable String password) {
		InputStream inStream = null;
		URL u = null;
		try {
			u = new URL(bundle.getString("url") + "/index.php?firstname="
					+ firstname + "&lastname=" + lastname + "&email=" + email
					+ "&password=" + password + "&route=api/customeradd");
			inStream = u.openStream();
		} catch (Exception e) {
			e.printStackTrace();
		}
		Scanner in = new Scanner(inStream);
		String str = null;
		while (in.hasNext()) {
			str = in.nextLine();
		}
		in.close();
		return str;
	}

	@RequestMapping(value = "/rest/edit/{firstname}/{lastname}/{email}/{telephone}/{id}", method = RequestMethod.GET)
	public @ResponseBody String edit(@PathVariable String firstname,
			@PathVariable String lastname, @PathVariable String email,
			@PathVariable String telephone, @PathVariable long id) {
		InputStream inStream = null;
		URL u = null;
		try {
			u = new URL(bundle.getString("url") + "/index.php?firstname="
					+ firstname + "&lastname=" + lastname + "&email=" + email
					+ "&telephone=" + telephone + "&id=" + id
					+ "&route=api/customeredit");
			inStream = u.openStream();
		} catch (Exception e) {
			e.printStackTrace();
		}
		Scanner in = new Scanner(inStream);
		String str = null;
		while (in.hasNext()) {
			str = in.nextLine();
		}
		in.close();
		return str;
	}

	@RequestMapping(value = "/rest/changepass/{email}/{oldpass}/{newpass}/{id}", method = RequestMethod.GET)
	public @ResponseBody String changepass(@PathVariable String email,
			@PathVariable String oldpass, @PathVariable String newpass,
			@PathVariable int id) {
		System.out.println("in changePassword");
		InputStream inStream = null;
		URL u = null;
		try {
			System.out.println("in changePassword");
			u = new URL(bundle.getString("url") + "/index.php?email=" + email
					+ "&oldpass=" + oldpass + "&newpass=" + newpass + "&id="
					+ id + "&route=api/changepassword");
			inStream = u.openStream();
		} catch (Exception e) {
			e.printStackTrace();
		}
		Scanner in = new Scanner(inStream);
		String str = null;
		while (in.hasNext()) {
			str = in.nextLine();
		}
		in.close();
		return str;
	}

}