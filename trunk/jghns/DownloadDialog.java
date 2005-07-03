import java.util.ArrayList;
import java.awt.*;
import java.awt.event.*;
import java.awt.image.*;
import java.net.*;
//import java.io.*;
import org.ghns.Entry;

public class DownloadDialog extends Frame implements ItemListener, ActionListener
{
	private ArrayList stufflist;
	private Choice choice;
	private Label version;
	private Label author;
	private Label name;
	private String preview;

	public DownloadDialog()
	{
		super("Get Hot New Stuff!");

		setSize(410, 300);
		setLayout(null);

		Label label = new Label("Select entry for installation:");
		label.setBounds(new Rectangle(5, 5, 390, 20));
		add(label);

		choice = new Choice();
		choice.addItemListener(this);
		choice.setBounds(new Rectangle(5, 30, 390, 30));
		add(choice);

		name = new Label("");
		name.setBounds(110, 65, 290, 20);
		add(name);

		author = new Label("");
		author.setBounds(110, 85, 290, 20);
		add(author);

		version = new Label("");
		version.setBounds(110, 105, 290, 20);
		add(version);

		Button install = new Button("Install");
		install.setBounds(5, 235, 100, 30);
		add(install);

		Button quit = new Button("Quit");
		quit.addActionListener(this);
		quit.setBounds(295, 235, 100, 30);
		add(quit);

		stufflist = new ArrayList();
	}

	public void paint(Graphics g)
	{
		if(preview == null) return;

		Toolkit tk = Toolkit.getDefaultToolkit();
		try
		{
			Image graphic = tk.getImage(new URL(preview));
			g.drawImage(graphic, 30, 90, this);
		}
		catch(MalformedURLException e)
		{
		}
	}

	public void itemStateChanged(ItemEvent event)
	{
		Choice selected = (Choice)event.getItemSelectable();
		String entryname = selected.getSelectedItem();

		for(int i = 0; i < stufflist.size(); i++)
		{
			Entry entry = (Entry)stufflist.get(i);
			if(entry.getName().equals(entryname))
			{
				name.setText("Name: " + entry.getName());
				author.setText("Author: " + entry.getAuthor());
				version.setText("Version: " + entry.getVersion());

				preview = entry.getPreview();
			}
		}
	}

	public void actionPerformed(ActionEvent e)
	{
		System.exit(0);
	}

	public void addEntry(Entry e)
	{
		stufflist.add(e);
		choice.addItem(e.getName());
	}
}

