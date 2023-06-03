package view;

import main.Config;

import javax.swing.*;

public class JeuMainView {
    protected JFrame jeuFrame;
    protected static JeuMainView objJeuMainView;

    private JeuMainView() {
    }
    public static JeuMainView  getJeuMainView(){
        if(objJeuMainView==null){
            objJeuMainView = new JeuMainView();
        }
        return objJeuMainView;
    }

    public void initializeView(){
        jeuFrame = new JFrame("Checker Board");
        jeuFrame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        jeuFrame.setSize(Config.frmSizeX,Config.frmSizeY);
        jeuFrame.setLocation(Config.frmLocX, Config.frmLocY);
        jeuFrame.setVisible(true);


    }
}
