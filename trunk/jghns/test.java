import java.util.ArrayList;
import org.w3c.dom.*;
import org.ghns.Helper;
import org.ghns.Provider;
import org.ghns.Entry;
import org.ghns.Engine;

class Test
{
	private ArrayList stufflist;

	Test()
	{
	}

	public void gethotnewstuff()
	{
		String url = "http://www.kstuff.org/hotstuff/directory/providers.xml";

		Engine engine = new Engine();
		stufflist = engine.getEntries(url);

		for(int i = 0; i < stufflist.size(); i++)
		{
			Entry entry = (Entry)stufflist.get(i);
			System.out.println(" * Stuff of type " + entry.getType());
			System.out.println("   Name: " + entry.getName());
		}
	}

	public void gui()
	{
		DownloadDialog dialog = new DownloadDialog();
		dialog.setVisible(true);

		for(int i = 0; i < stufflist.size(); i++)
		{
			Entry entry = (Entry)stufflist.get(i);
			dialog.addEntry(entry);
		}
	}

	public static void main(String args[])
	{
		Test test = new Test();
		test.gethotnewstuff();
		test.gui();
	}
}

